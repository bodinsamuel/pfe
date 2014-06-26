<?php namespace Custom\Elastic;

class Post extends Base
{
    public $index_type = 'posts';

    // Errors
    const E_MISSING_POS = -1;

    public function init()
    {
        parent::create_index();
        sleep(1);

        $this->put_mapping;
    }

    public function insert($posts, $upsert = FALSE)
    {
        if (empty($posts))
            return FALSE;

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
                'content' => $post->content,
                'url' => $post->url,
                'cover' => [
                    'id_media' => (int)$post->id_cover,
                    'hash' => $post->cover_hash,
                    'title' => $post->cover_title,
                    'extension' => $post->cover_extension
                ],
                'address' => [
                    'country' => (int)$post->country_code,
                    'id_state' => (string)$post->admin1_id,
                    'id_province' => (int)$post->admin2_id,
                    'id_city' => (int)$post->city_id
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
            // parent::delete_index();
            // sleep(1);
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
                'content' => [ 'type' => 'string', 'index' => 'no' ],
                'url' => [ 'type' => 'string', 'index' => 'no' ],
                'cover' => [
                    'properties' => [
                        'id_media' => [ 'type' => 'integer'],
                        'hash' => [ 'type' => 'string'],
                        'title' => [ 'type' => 'string'],
                        'extension' => [ 'type' => 'string']
                    ],
                    'index' => 'no'
                ],
                'address' => [
                    'properties' => [
                        'country' => [ 'type' => 'string', 'index' => 'not_analyzed' ],
                        'id_state' => [ 'type' => 'integer' ],
                        'id_province' => [ 'type' => 'integer' ],
                        'id_city' => [ 'type' => 'integer' ],
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
        if (!isset($opts['id_city']) && !isset($opts['coord'])
            && !isset($opts['country']) && !isset($opts['_id']))
        {
            return self::E_MISSING_POS;
        }

        $elastic = new Search();

        // limit [0, 100]
        $offset = isset($opts['offset']) && $opts['offset'] > 0 ? (int)$opts['offset'] : 0;
        $limit = isset($opts['limit']) && $opts['limit'] <= 100 && $opts['limit'] > 0 ? (int)$opts['limit'] : 20;
        $elastic->setLimit($offset, $limit);

        // ids
        $t = [];
        if (isset($opts['id_city']) && !empty($opts['id_city']))
            $t['terms']['address.id_city'] = (array)$opts['id_city'];
        if (isset($opts['id_province']) && !empty($opts['id_province']))
            $t['terms']['address.id_province'] = (array)$opts['id_province'];
        if (isset($opts['id_state']) && !empty($opts['id_state']))
            $t['terms']['address.id_state'] = (array)$opts['id_state'];
        if (isset($opts['country']))
            $t['terms']['address.country'] = (array)$opts['country'];
        if (isset($opts['_id']))
            $t['terms']['_id'] = (array)$opts['_id'];

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
        $run['markers'] = [];
        if (empty($run['results']))
            return $run;

        $results = [];
        $markers = [];
        foreach ($run['results'] as $data)
        {
            $results[$data['_id']] = $data['_source'];
            $results[$data['_id']]['_score'] = $data['_score'];

            $markers[$data['_id']] = [
                'x'     => $data['_source']['location']['lon'],
                'y'     => $data['_source']['location']['lat'],
                'title' => \Custom\Post::make_title($data['_source']['id_property_type'], $data['_source']['details']['surface_living']),
                'image' => \Custom\Media::url($data['_source']['cover'], '150x100'),
                'url'  => isset($data['_source']['url']) ? $data['_source']['url'] : ''
            ];
        }

        // $run['markers_center'] = \Custom\Geo::get_center_of_geocoord($markers);

        $run['results'] = $results;
        $run['markers'] = $markers;
        return $run;
    }

}
