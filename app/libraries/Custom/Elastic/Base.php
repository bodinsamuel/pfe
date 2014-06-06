<?php namespace Custom\Elastic;

class Base
{
    protected $client;
    protected $index = 'pfe';

    public function __construct()
    {
        $params = [];
        $params['hosts'] = [
            '192.168.56.101'
        ];

        $this->client = new \Elasticsearch\Client($params);
    }

    public function fill_params(&$params)
    {
        $params['index'] = $this->index;
        $params['type'] = $this->index_type;

        return $params;
    }

    public function search($params)
    {
        $params = $this->fill_params($params);

        // print_r($params);
        // die();
        return $this->client->search($params);
    }

    public function create($params)
    {
        $params = $this->fill_params($params);

        return $this->client->index($params);
    }

    public function select($id)
    {
        $params = $this->fill_params([]);
        $params['id'] = $id;

        return $this->client->get($params);
    }

    public function delete($id)
    {
        $params = $this->fill_params([]);
        $params['id'] = $id;

        return $this->client->delete($params);
    }

    public function bulk($params)
    {
        return $this->client->bulk($params);
    }

    public function create_index()
    {
        $params['index'] = $this->index;
        $this->client->indices()->create($params);
    }

    public function delete_index()
    {
        $params['index'] = $this->index;
        $this->client->indices()->delete($params);
    }
}
