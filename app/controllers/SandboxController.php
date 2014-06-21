<?php

class SandboxController extends BaseController
{
    protected $layout = NULL;

    public function getResize()
    {
        $resizer = new Custom\Media\Resizer;
        $resizer->setSource('/var/www/pfe.dev/media\0a9\64b\f71\0a964bf7165f6dff3f0e6190d8f7583a/original.jpg');
        $resizer->setExtension('jpg');
        $resizer->filler(100, 100);

        $resizer = new Custom\Media\Resizer;
        $resizer->setSource('/var/www/pfe.dev/media\0af\24a\b84\0af24ab8488da29ecd0de6bdd0068da1/original.jpg');
        $resizer->setExtension('jpg');
        $resizer->filler(100, 100);
    }

    public function getRabbit()
    {
        $bean = Custom\Singleton::getBeanstalkd();
        $bean->sendEvents([
            'action' => 'PostElasticUpsert',
            'data' => [
                'id_post' => 156
            ]
        ]);
        die();

        echo 'test';
        $connection = new PhpAmqpLib\Connection\AMQPConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('elastic_upsert', false, true, false, false);

        $message = 'Hello World!';
        $properties = ['delivery_mode' => 2];
        $msg = new PhpAmqpLib\Message\AMQPMessage($message, $properties);
        $channel->basic_publish($msg, '', 'elastic_upsert');

        $channel->close();
        $connection->close();
    }

    public function getElastic()
    {

        $elastic = new Custom\Elastic\Post();
        // $elastic->put_mapping(TRUE);
        // die();
        $post = Custom\Post::select([156,157,158,159,160,162,164,165,166,167,168,169,170,171,172,173,174,175,176,177,178,179,180,181,182,183,184,185,186,187,188,189,190,191,192,193,194,195,196,197], ['galleries' => FALSE, 'markers' => FALSE]);
        $elastic->insert($post['posts']);
        die();


        $elastic = new Custom\Elastic\Search();
        $elastic->setLimit(0, 5);
        $elastic->addQuery('bool', ['should' => [
            'range' => [
                'price' => [
                    'gte' => 1200,
                    'lte' => 1210,
                    'boost' => 1.5
                ]
            ]
        ]]);
        // $elastic->addFilter('and', [
        //     'terms' => [
        //         'id_property_type' => [
        //             1
        //         ]
        //     ]
        // ]);
        // $elastic->addFilter('and', [
        //     'geo_distance' => [
        //         'distance' => '120km',
        //         'location' => [
        //             'lat' => 48,
        //             'lon' => 2
        //         ]
        //     ]
        // ]);
        // $elastic->addSort('_geo_distance', [
        //     'order' => 'desc',
        //     'unit' => 'km',
        //     'location' => [
        //         'lat' => 48,
        //         'lon' => 2
        //     ]
        // ]);

        $search = $elastic->run();
        print_r($search);
        die();
    }

    public function postUpload()
    {
        print_r(Input::all());
        die();
        $manager = new Custom\Media\Uploader();
        $manager->setAllowed(['image/jpeg', 'image/png']);
        $manager->setDir('/var/www/pfe.dev/media');
        $manager->setMaxFileSize(1024 * 1024 * 10);


        $upload = $manager->handleUrl('http://8.visuels.poliris.com/2d/8/a/0/4/8a04b624-21d9.jpg');
        var_dump($infos);

        $inputs = Input::all();
        $infos = $manager->handle($inputs['file']->getPathname());
        var_dump($infos);
    }

    public function getTest()
    {

        $address = Custom\Address::get(1);
        print_r($address);
        die();
        $input = [
            'id_city' => rand(1,100),
            'id_district' => rand(1,100),
            'id_street_type' => rand(1,200),
            'street_number' => rand(1,300),
            'street_name' => 'Pasteur',
            'other' => 'CEDEX 10',
            'primary' => TRUE,
            'origin' => 'search',
        ];
        Custom\Address::upsert($input);
    }

    public function getBencheloquentinsert()
    {
        $start = microtime(TRUE);
        for ($i=0; $i < 1000; $i++)
        {
            $id = DB::table('addresses')->insertGetId(
                [
                    'id_city' => rand(1,100),
                    'id_district' => rand(1,100),
                    'id_street_type' => rand(1,200),
                    'street_number' => rand(1,300),
                    'street_name' => 'Pasteur',
                    'other' => 'CEDEX 10',
                    'primary' => TRUE,
                    'origin' => 'search',
                ]
            );
        }
        $end = microtime(TRUE);
        var_dump(round($end - $start, 6));

        $start = microtime(TRUE);
        for ($i=0; $i < 1000; $i++)
        {
            $input = [
                'id_city' => rand(1,100),
                'id_district' => rand(1,100),
                'id_street_type' => rand(1,200),
                'street_number' => rand(1,300),
                'street_name' => 'Pasteur',
                'other' => 'CEDEX 10',
                'primary' => TRUE,
                'origin' => 'search',
            ];
            Custom\Address::upsert($input);
        }
        $end = microtime(TRUE);
        var_dump(round($end - $start, 6));
        die();
    }

    public function getBencheloquentselect($value='')
    {
        $start = microtime(TRUE);
        for ($i=0; $i < 1000; $i++)
        {
            $address = DB::table('addresses')->where('id_address', '=', rand(1, 99999))->first();
        }
        $end = microtime(TRUE);
        var_dump(round($end - $start, 6));

        $start = microtime(TRUE);
        for ($i=0; $i < 1000; $i++)
        {
            $address =  Custom\Address::get(rand(1, 99999));
        }
        $end = microtime(TRUE);
        var_dump(round($end - $start, 6));
    }

    public function getAddsafenametocities()
    {
        $query = 'SELECT id_city, name
                    FROM geo_cities';
        $results = \DB::select($query);

        foreach ($results as $value)
        {
            $q2 = 'UPDATE geo_cities
                      SET safe = "' . str_replace('-arrondissement', '', Str::slug($value->name)) . '"
                    WHERE id_city = ' . (int)$value->id_city;
            \DB::statement($q2);
        }
    }

    public function getAddsafenametocountries()
    {
        $query = 'SELECT id_country, name_full
                    FROM geo_countries
                    WHERE safe IS NULL OR safe = ""';
        $results = \DB::select($query);

        foreach ($results as $value)
        {
            $q2 = 'UPDATE geo_countries
                      SET safe = "' . Str::slug($value->name_full) . '"
                    WHERE id_country = ' . (int)$value->id_country;
            \DB::statement($q2);
        }
    }

    public function getAddsafenametoprovinces()
    {
        $query = 'SELECT id_province, name
                    FROM geo_provinces
                    WHERE safe IS NULL OR safe = ""';
        $results = \DB::select($query);

        foreach ($results as $value)
        {
            $q2 = 'UPDATE geo_provinces
                      SET safe = "' . Str::slug($value->name) . '"
                    WHERE id_province = ' . (int)$value->id_province;
            \DB::statement($q2);
        }
    }

    public function getAddsafenametostates()
    {
        $query = 'SELECT id_state, name
                    FROM geo_states
                    WHERE safe IS NULL OR safe = ""';
        $results = \DB::select($query);

        foreach ($results as $value)
        {
            $q2 = 'UPDATE geo_states
                      SET safe = "' . Str::slug($value->name) . '"
                    WHERE id_state = ' . (int)$value->id_state;
            \DB::statement($q2);
        }
    }

    public function getAddprovincestocities()
    {
        $query = 'SELECT id_city, geo_cities.name, zipcode, geo_provinces.id_province,
                         geo_provinces.name AS PNAME, SUBSTRING(geo_cities.zipcode, 1, 2) AS test
                    FROM geo_cities
               LEFT JOIN geo_provinces
                         ON geo_provinces.iso1 = SUBSTRING(geo_cities.zipcode, 1, 2)
                    WHERE geo_cities.id_province IS NULL OR geo_cities.id_province = 0
                    ORDER BY geo_cities.name';
        $results = \DB::select($query);

        foreach ($results as $value)
        {
            $q2 = 'UPDATE geo_cities
                      SET id_province = "' . Str::slug($value->id_province) . '"
                    WHERE id_city = ' . (int)$value->id_city;
            \DB::statement($q2);
        }
    }
}
