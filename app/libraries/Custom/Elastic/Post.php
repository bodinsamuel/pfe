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
            $body = [
                'id_post_type' => (int)$post->id_post_type,
                'id_property_type' => (int)$post->id_property_type,
                'date_updated' => $post->date_updated,
                'exclusivity' => (bool)$post->exclusivity,
                'price' => (int)$post->price,
                'has_photo' => (int)$post->has_photo,
                'id_cover' => (int)$post->id_cover,
                'address' => [
                    'zipcode' => (int)$post->zipcode,
                    'country' => (int)$post->id_country
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
                'exclusivity' => [ 'type' => 'boolean' ],
                'price' => [ 'type' => 'integer' ],
                'has_photo' => [ 'type' => 'integer' ],
                'id_cover' => [ 'type' => 'integer' ],
                'address' => [
                    'properties' => [
                        'zipcode' => [ 'type' => 'integer' ],
                        'country' => [ 'type' => 'integer' ]
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
        if ((!isset($opts['ids']) && !isset($opts['coord'])) || empty($opts['ids']))
        {
            return self::E_MISSING_POS;
        }

        $elastic = new Search();

        // limit [0, 100]
        $offset = isset($opts['offset']) && $opts['offset'] > 0 ? (int)$opts['offset'] : 0;
        $limit = isset($opts['limit']) && $opts['limit'] < 100 && $opts['limit'] > 0 ? (int)$opts['limit'] : 20;
        $elastic->setLimit($offset, $limit);

        // ids
        if (isset($opts['ids']))
        {
            if (isset($opts['ids']['zipcode']))
            {
                $t = [];
                $t['terms']['address.zipcode'] = (array)$opts['ids']['zipcode'];
                $elastic->addFilter('and', $t);
            }
            if (isset($opts['ids']['country']))
            {

            }
        }

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

        }

        // Room
        if (isset($opts['room']))
        {

        }

        // Run search
        return $elastic->run();
    }

}
