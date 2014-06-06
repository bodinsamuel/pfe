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
                'post' => [
                    'id_post_type' => $post->id_post_type,
                    'id_property_type' => $post->id_property_type,
                    'date_updated' => $post->date_updated,
                    'exclusivity' => $post->exclusivity
                ],
                'location' => [
                    'lat' => (double)$post->latitude,
                    'lon' => (double)$post->longitude
                ],
                'details' => [
                    'surface_living' => $post->surface_living,
                    'room' => $post->room
                ],
                'price' => [
                    'current' => $post->price
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

    public function mapping()
    {
        $mapping = [
            '_source' => [
                'enable' => TRUE
            ],
            'properties' => [
                'location' => [
                    'type' => 'geo_point'
                ]
            ]
        ];
        $params['body'][$this->index_type] = $mapping;
        $this->fill_params($params);
        $this->client->indices()->putMapping($params);
    }

}
