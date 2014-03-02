<?php

class AccountController extends BaseController
{

    public function get_Dashboard()
    {
        $data = ['__page_title' => 'Dashboard'];
        return View::make('account/dashboard', $data);
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
        if (Auth::attempt($user))
        {
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

    public function get_ResetPassword()
    {
        # code...
    }

    public function post_ResetPassword()
    {
        # code...
    }

    public function get_ForgotPassword()
    {
        $data = ['__page_title' => 'Forgot Password'];
        return View::make('account/forgot_password', $data);
    }

    public function post_ForgotPassword()
    {
        $validator = Validator::make(
            Input::all(), [
                'email' => 'required|email'
            ]);

        if (!$validator->fails())
        {
            // Get user
            $user = User::where('email', '=', Input::get('email'))->first();
            if ($user !== NULL)
            {
                // Insert token in database
                $token = Hash::make($user->email . $user->id . time());
                $saved = Token::set('reset_password', $token, $user->email);

                // Insertion succesfull
                if ($saved === TRUE)
                {
                    $data = (array)$user;
                    $data['token'] = $token;
                    // Send mail
                    Mail::send('emails.auth.forgot_password', $data, function($message) use ($user)
                    {
                        $message->from($conf['address'], $conf['name']);
                        $message->to($user->email);
                    });
                }
                else
                {
                    $error = Lang::get('global.error.oops');
                    return Redirect::to('/account/forgot_password')->withInput()->with('flash.notice.error', $error);
                }
            }

            // Return to Home with message
            $success = Lang::get('account.success.send_forgot_password');
            return Redirect::to('/')->with('flash.notice.success', $success);
        }

        return Redirect::to('/account/forgot_password')->withInput()->withErrors($validator);
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
