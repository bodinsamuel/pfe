<?php

class HomeController extends BaseController
{

    /**
     * Homepage
     */
    public function run()
    {
        $data = [
            '__map' => [
                'locate' => TRUE
            ]
        ];

        return View::make('default/home/home', $data);
    }

}
