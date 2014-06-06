<?php namespace Custom\Elastic;

class Post extends Base
{
    public $index_type = 'posts';

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
                'id_post_type' => $post->id_post_type,
                'id_property_type' => $post->id_property_type,
                'date_updated' => $post->date_updated,
                'exclusivity' => $post->exclusivity,
                'price' => $post->price,
                'location' => [
                    'lat' => (double)$post->latitude,
                    'lon' => (double)$post->longitude
                ],
                'details' => [
                    'surface_living' => $post->surface_living,
                    'room' => $post->room
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

    public function create_index()
    {
        // Delete index just in case
        $params['index'] = $this->index;
        $this->client->indices()->delete($params);

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
                'location' => [
                    'type' => 'geo_point'
                ],
                'details' => [
                    'properties' => [
                    ]
                ]
            ]
        ];

        $params = [];
        $params['index'] = $this->index;
        $params['body'] = [
            'mappings' => [
                $this->index_type => $mapping
            ]
        ];
        $this->client->indices()->create($params);
    }

}
