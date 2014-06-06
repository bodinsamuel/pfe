<?php

class SandboxController extends BaseController
{
    protected $layout = NULL;

    public function getElastic()
    {

        $elastic = new Custom\Elastic\Post();
        // $elastic->put_mapping(TRUE);
        // die();
        $post = Custom\Post::select([20, 21, 22], ['galleries' => FALSE]);
        $elastic->insert($post['posts']);
        die();


        $elastic = new Custom\Elastic\Search();
        // $res = $elastic->search('{
        //     "query": {
        //         "filtered": {
        //             "query": {},
        //             "filter": {
        //                 "range": {
        //                     "price": {
        //                         "gte": 800,
        //                         "lte": 2000
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // }');
        // print_r($res);
        // die();
        $elastic->setLimit(0, 5);
        // $elastic->addSort('_score', 'asc');
        // $elastic->addFilter('and', ['post.id_property_type' => 1]);
        $elastic->addQuery('bool', ['should' => [
            'range' => [
                'price' => [
                    'gte' => 1300,
                    'lte' => 2000,
                    'boost' => 1.5
                ]
            ]
        ]]);
        $elastic->addFilter('and', [
            'terms' => [
                'id_property_type' => [
                    1
                ]
            ]
        ]);
        $elastic->addFilter('and', [
            'geo_distance' => [
                'distance' => '120km',
                'location' => [
                    'lat' => 48,
                    'lon' => 2
                ]
            ]
        ]);
        $elastic->addSort('_geo_distance', [
            'order' => 'desc',
            'unit' => 'km',
            'location' => [
                'lat' => 48,
                'lon' => 2
            ]
        ]);

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
}
