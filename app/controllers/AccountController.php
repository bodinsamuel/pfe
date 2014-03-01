<?php

class AccountController extends BaseController
{

    public function get_Dashboard()
    {
        return View::make('account/dashboard');
    }

    public function get_Register()
    {
        $data = ['__page_title' => 'Register'];
        return View::make('account/register', $data);
    }

    public function post_Register()
    {
        $data = ['__page_title' => 'Register'];

        $validator = User::validateRegister();
        if (!$validator->fails())
        {
            $user = new User;
            $user->email = Input::get('email');
            $user->status = 0;
            $user->password = Hash::make(Input::get('password'));
            $inserted = $user->save();

            // Insertion succesfull
            if ($inserted === TRUE)
            {
                return Redirect::to('/')
                        ->with('flash_notice', 'You have succesfully register, '
                                                . 'please validate your account by clicking '
                                                . 'the link on the email you just recieved.');
            }
            else
            {

            }
        }

        return Redirect::to('register')->withInput()->withErrors($validator);
    }

    public function get_Login()
    {
        # code...
    }

    public function post_Login()
    {
        # code...
    }

    public function get_Logout()
    {
        # code...
    }

    public function get_Deactivate()
    {
        # code...
    }

    public function post_Deactivate()
    {
        # code...
    }

    public function get_resetPassword()
    {
        # code...
    }

    public function get_alert()
    {
        # code...
    }

    public function get_Edit()
    {
        # code...
    }

    public function post_Edit()
    {
        # code...
    }
}
