<?php

class UserDashboardController extends BaseController
{
    public function get()
    {
        $data = ['__page_title' => 'Account Dashboard'];
        return View::make('default/account/dashboard', $data);
    }

    public function getAlert()
    {
        # code...
    }

    public function getFavorite()
    {
        $data = ['__page_title' => 'Account Settings'];

        $data['favorites'] = \Custom\Favorite::select(['id_user' => Session::get('id_user')]);

        return View::make('default/account/favorite', $data);
    }

    public function getEdit()
    {
        $data = ['__page_title' => 'Account Settings'];
        return View::make('default/account/edit', $data);
    }

    public function postEdit()
    {
        $update = \Custom\User\Dashboard::updateInformations(Input::all());

        if (!is_array($update) || !empty($update['errors']))
        {
            return Redirect::to('/account/edit')->withInput()
                            ->withErrors($update['errors']);

        }
        return Redirect::to('/account/edit');
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
        return View::make('default/account/edit', $data);
    }

    public function postChangepassword()
    {
        $update = \Custom\User\Dashboard::updatePassword(Input::all());

        if (!is_array($update) || !empty($update['errors']))
        {
            return Redirect::to('/account/edit')->withInput()
                            ->withErrors($update['errors']);

        }
        return Redirect::to('/account/edit');
    }
}
