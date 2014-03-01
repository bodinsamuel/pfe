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
                $success = Lang::get('account.success.register');
                return Redirect::to('/')->with('flash.notice.success', $success);
            }
        }

        return Redirect::to('register')->withInput()->withErrors($validator);
    }

    public function get_Login()
    {
        $data = ['__page_title' => 'Login'];
        return View::make('account/login', $data);
    }

    public function post_Login()
    {
        $user = [
            'email' => Input::get('email'),
            'password' => Input::get('password')
        ];

        // Logged
        if (Auth::attempt($user)) {
            $success = Lang::get('account.success.login');
            return Redirect::to('/')->with('flash.notice.success', $success);
        }

        $error = Lang::get('account.error.login');
        return Redirect::to('login')->withInput()->with('flash.notice.error', $error);
    }

    public function get_Logout()
    {
        Auth::logout();

        $success = Lang::get('account.success.logout');
        return Redirect::to('/')->with('flash.notice.success', $success);
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
