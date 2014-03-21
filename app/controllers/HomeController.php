<?php

class HomeController extends BaseController
{

    /**
     * Homepage
     */
    public function run()
    {
        $data = ['__with_bg_map' => TRUE,
                 'map_config' => ['locate' => TRUE]];

        return View::make('home/home', $data);
    }

}
