<?php

class SearchController extends BaseController
{
    public function get_Run()
    {
        $data = ['__with_bg_map' => TRUE,
                 'map_config' => ['locate' => TRUE]];

        return View::make('modules/search/main', $data);
    }

    public function save($id_search = NULL)
    {
        # code...
    }

    public function delete($id_search)
    {
        # code...
    }
}
