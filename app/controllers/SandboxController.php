<?php

class SandboxController extends BaseController
{
    protected $layout = NULL;

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
