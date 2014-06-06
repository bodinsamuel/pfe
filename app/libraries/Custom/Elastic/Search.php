<?php namespace Custom\Elastic;

class Search extends Base
{
    public $index_type = 'posts';
    protected $queries;
    protected $filters;

    protected $fields;

    protected $from;
    protected $size;

    protected $sort;

    public function __construct()
    {
        $this->setLimit();
        $this->resetFields();
        $this->resetQuery();
        $this->resetFilter();

        parent::__construct();
    }

    public function addFilter($term, $filter)
    {
        if (!isset($this->filters[$term]))
            $this->filters[$term] = [];

        $this->filters[$term][] = $filter;
    }

    public function resetFilter()
    {
        $this->filters = [];
    }

    public function addQuery($term, $query)
    {
        if (!isset($this->queries[$term]))
            $this->queries[$term] = [];

        $this->queries[$term] = $query;
    }

    public function resetQuery()
    {
        $this->queries = [];
    }

    public function setFields($field, $exclude = FALSE)
    {
        if ($exclude === TRUE)
            $this->fields['exclude'][] = $field;
        else
            $this->fields['include'][] = $field;
    }

    public function resetFields()
    {
        $this->fields = [];
    }

    public function setLimit($offset = 0, $limit = 20)
    {
        $this->from = $offset;
        $this->size = $limit;
    }

    public function addSort($name, $order = 'desc')
    {
        $this->sort[] = [$name => $order];
    }

    public function resetSort($name, $order)
    {
        $this->sort = [];
    }

    public function setGeo($lat, $lon, $order = 'desc', $unit = 'km')
    {
        $this->sort['_geo_distance'] = [
            'pin.location' => [
                (double)$lat,
                (double)$lon
            ],
            'order' => $order,
            'unit' => $unit
        ];
    }

    public function resetGeo()
    {
        if (isset($this->sort['_geo_distance']))
            unset($this->sort['_geo_distance']);
    }

    public function run()
    {
        if (empty($this->queries) && empty($this->filters))
            throw new \Exception('[Elastic] empty query');

        // Initial data
        $params = [
            'body'  => [
                'from' => $this->from,
                'size' => $this->size,
            ]
        ];

        // Filters and query
        if (!empty($this->filters))
        {
            $params['body']['query']['filtered'] = [];
            $params['body']['query']['filtered']['filter'] = $this->filters;

            // Query should always be sent, even empty
            $params['body']['query']['filtered']['query'] = $this->queries;
        }
        else
        {
            $params['body']['query'] = $this->queries;
        }

        // Sorting
        if (!empty($this->sort))
            $params['body']['sort'] = $this->sort;

        return parent::search($params);
    }
}
