<?php

class UserDashboardController extends BaseController
{
    public function get()
    {
        $data = ['__page_title' => 'Account Dashboard'];
        return View::make('account/dashboard', $data);
    }

    public function getAlert()
    {
        # code...
    }

    public function getFavorite()
    {
        $data = ['__page_title' => 'Account Settings'];

        $data['favorites'] = \Custom\Favorite::select(['id_user' => Session::get('id_user')]);
        // print_r($data);
        // die();
        return View::make('account/favorite', $data);
    }

    public function getEdit()
    {
        $data = ['__page_title' => 'Account Settings'];
        return View::make('account/edit', $data);
    }

    public function postEdit()
    {
        # code...
    }

    public function getDeactivate()
    {
        # code...
    }

    public function postDeactivate()
    {
        # code...
    }

    public function getChangepassword()
    {
        $data = ['__page_title' => 'Account Settings'];
        return View::make('account/edit', $data);
    }

    public function postChangepassword()
    {
        # code...
    }
}
