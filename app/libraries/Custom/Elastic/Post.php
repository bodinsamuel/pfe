<?php namespace Custom\Elastic;

class Post extends Base
{
    public $index_type = 'posts';

    // Errors
    const E_MISSING_POS = -1;


    public function insert($posts, $upsert = FALSE)
    {
        $params = [];
        $params1 = [];
        $params1 = [
            '_index' => $this->index,
            '_type' => $this->index_type,
        ];
        foreach ($posts as $id => $post)
        {
            $cover = \Custom\Media::select($post->id_cover);
            $cover = empty($cover) ? [] : (array)$cover[0];

            $body = [
                'id_post_type' => (int)$post->id_post_type,
                'id_property_type' => (int)$post->id_property_type,
                'date_updated' => $post->date_updated,
                'exclusivity' => (bool)$post->exclusivity,
                'price' => (int)$post->price,
                'has_photo' => (int)$post->has_photo,
                'cover' => [
                    'id_media' => (int)$post->id_cover,
                    'url' => \Custom\Media::url($cover, 'original')
                ],
                'address' => [
                    'zipcode' => (int)$post->zipcode,
                    'country' => (string)$post->country_code
                ],
                'location' => [
                    'lat' => (double)$post->latitude,
                    'lon' => (double)$post->longitude
                ],
                'details' => [
                    'surface_living' => (int)$post->surface_living,
                    'room' => (int)$post->room,
                ],
            ];

            $params1['_id'] = $id;
            $params['body'][] = [
                'update' => $params1
            ];

            $params['body'][] = [
                'doc_as_upsert' => 'true',
                'doc' => $body
            ];
        }

        return $this->bulk($params);
    }

    public function put_mapping($reset = FALSE)
    {
        if ($reset === TRUE)
        {
            parent::delete_index();
            sleep(1);
            parent::create_index();
            sleep(1);
        }

        // prepare mapping
        $mapping = [
            '_source' => [
                'enable' => TRUE
            ],
            'properties' => [
                'id_post_type' => [ 'type' => 'integer' ],
                'id_property_type' => [ 'type' => 'integer' ],
                'last_updated' => [
                    'type' => 'date',
                    'format' => 'YYYY-MM-dd HH:mm:ss'
                ],
                'exclusivity' => [ 'type' => 'boolean'],
                'price' => [ 'type' => 'integer' ],
                'has_photo' => [ 'type' => 'integer' ],
                'cover' => [
                    'properties' => [
                        'id_media' => [ 'type' => 'integer'],
                        'mime' => [ 'type' => 'string'],
                        'url' => [ 'type' => 'string']
                    ],
                    'index' => 'no'
                ],
                'address' => [
                    'properties' => [
                        'zipcode' => [ 'type' => 'integer' ],
                        'country' => [ 'type' => 'string', 'index' => 'not_analyzed' ]
                    ]
                ],
                'location' => [
                    'type' => 'geo_point'
                ],
                'details' => [
                    'properties' => [
                        'surface_living' => [ 'type' => 'integer' ],
                        'room' => [ 'type' => 'integer' ]
                    ]
                ]
            ]
        ];

        $params = [];
        $params['index'] = $this->index;
        $params['type'] = $this->index_type;
        $params['body'] = [
            $this->index_type => $mapping
        ];
        $this->client->indices()->putMapping($params);
    }

    public function search($opts = [])
    {
        // initial check
        if (!isset($opts['zipcode']) && !isset($opts['coord'])
            && !isset($opts['country']))
        {
            return self::E_MISSING_POS;
        }

        $elastic = new Search();

        // limit [0, 100]
        $offset = isset($opts['offset']) && $opts['offset'] > 0 ? (int)$opts['offset'] : 0;
        $limit = isset($opts['limit']) && $opts['limit'] < 100 && $opts['limit'] > 0 ? (int)$opts['limit'] : 20;
        $elastic->setLimit($offset, $limit);

        // ids
        $t = [];
        if (isset($opts['zipcode']))
            $t['terms']['address.zipcode'] = (array)$opts['zipcode'];
        if (isset($opts['country']))
            $t['terms']['address.country'] = (array)$opts['country'];

        if (!empty($t))
            $elastic->addFilter('and', $t);


        // Geo dist
        if (isset($opts['coord']))
        {

        }

        // Price
        if (isset($opts['price']))
        {
            $t = [];
            if (isset($opts['price']['min']))
                $t['range']['price']['gte'] =  $opts['price']['min'] < 0 ? 0 : (int)$opts['price']['min'];

            if (isset($opts['price']['max']))
                $t['range']['price']['lte'] = (int)$opts['price']['max'];

            if (!empty($t))
                $elastic->addFilter('and', $t);
        }

        // Exclu
        if (isset($opts['exclusivity']))
        {
            $elastic->addFilter('and', [
                'term' => [
                    'exclusivity' => true
                ]
            ]);
        }

        // Room
        if (isset($opts['room']))
        {

        }

        // Run search
        $run = $elastic->run();
        if (empty($run['results']))
            return $run;

        $results = [];
        foreach ($run['results'] as $data)
        {
            $results[$data['_id']] = $data['_source'];
            $results[$data['_id']]['_score'] = $data['_score'];
        }

        $run['results'] = $results;
        return $run;
    }

}
