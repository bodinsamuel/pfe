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
        if ($validator->fails())
            return Redirect::to('register')->withInput()->withErrors($validator);

        $user = new User;
        $user->email = Input::get('email');
        $user->status = 0;
        $user->password = Hash::make(Input::get('password'));
        $user->date_created = date('Y-m-d H:i:s');
        $inserted = $user->save();

        // Insertion failed
        if ($inserted !== TRUE)
            return Redirect::to('register')->withInput();

        // Insert token in database, for email validation
        $token = Hash::make($user->email . $user->id . time());
        $saved = Token::add('validate_account', $token, $user->email);

        // Send mail
        Mail::send('emails.account.register', $data, function($message) use ($user)
        {
            $message->to($user->email)
                    ->subject('Welcome');
        });

        $success = Lang::get('account.success.register');
        return Redirect::to('/')->with('flash.notice.success', $success);
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
            if (Auth::user()->status === 1)
            {
                $success = Lang::get('account.success.login');
                return Redirect::to('/')->with('flash.notice.success', $success);
            }
            else
            {
                Auth::logout();
                $error = Lang::get('account.error.email_not_verified', ['mail' => $user['email']]);
                return Redirect::to('login')->with('flash.notice.error', $error);
            }
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

    public function get_validate()
    {
        if (Token::ensure('validate_account') === FALSE)
            return Redirect::To('/');

        // Get user
        $user = User::where('email', '=', Input::get('email'))->first();
        if ($user === NULL)
            return Redirect::To('/');

        $user->date_validated = date('Y-m-d H:i:s');
        $saved = $user->save();

        // Insertion not succesfull
        if ($saved !== TRUE)
            return oops('/');

        $confirm = Token::confirm('validate_account', Input::get('token'), Input::get('email'));

        Auth::attempt($user);

        $success = Lang::get('account.success.validation.done');
        return Redirect::to('/')->with('flash.notice.success', $success);
    }

    public function get_sendValidation()
    {
        $email = Input::get('email');
        $user = User::where('email', '=', $email)->first();

        if ($user !== NULL && $user->isValidated() === FALSE)
        {
            // Insert token in database, for email validation
            $token = Hash::make($user->email . $user->id . time());
            $saved = Token::add('validate_account', $token, $user->email);

            // Prepare data for email
            $data['user'] = (array)$user['original'];
            $data['token'] = $token;

            // Send mail
            Mail::send('emails.account.validate', $data, function($message) use ($user)
            {
                $message->to($user->email)
                        ->subject('Account Validation');
            });
        }

        $success = Lang::get('account.success.validation.sent');
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

    public function get_ForgotPassword()
    {
        $data = ['__page_title' => 'Forgot Password'];
        return View::make('account/password/forgotten', $data);
    }

    public function post_ForgotPassword()
    {
        $validator = Validator::make(
            Input::all(), [
                'email' => 'required|email'
            ]);
        if ($validator->fails())
            return Redirect::to('/password/forgotten')->withInput()->withErrors($validator);

        // Get user
        $user = User::where('email', '=', Input::get('email'))->first();
        if ($user !== NULL)
        {
            // Insert token in database
            $token = Hash::make($user->email . $user->id . time());
            $saved = Token::add('reset_password', $token, $user->email);

            // Insertion failed
            if ($saved !== TRUE)
                return oops('/password/forgotten');

            // Prepare data for email
            $data['user'] = (array)$user['original'];
            $data['token'] = $token;

            // Send mail
            Mail::send('emails.account.forgot_password', $data, function($message) use ($user)
            {
                $message->to($user->email)
                        ->subject('Password Reset');
            });
        }

        // Return to Home with message
        // Wether user exist or not, to not give hacker any clue
        $success = Lang::get('account.success.password.send_forgot');
        return Redirect::to('/')->with('flash.notice.success', $success);
    }

    public function get_ResetPassword()
    {
        $data = ['__page_title' => 'Reset Password'];

        if (Token::ensure('reset_password') === FALSE)
            return Redirect::To('/');

        return View::make('account/password/reset', $data);
    }

    public function post_ResetPassword()
    {
        if (Token::ensure('reset_password') === FALSE)
            return Redirect::To('/');

        // Verify new password
        $validator = User::validatePasswordReset();
        if ($validator->fails())
        {
            return Redirect::route('password_reset', $_GET)
                            ->withInput()
                            ->withErrors($validator);
        }

        // Get user
        $user = User::where('email', '=', Input::get('email'))->first();
        $user->password = Hash::make(Input::get('password'));
        $saved = $user->save();

        // Insertion succesfull
        if ($saved !== TRUE)
            return oops('/password/reset');

        $confirm = Token::confirm('reset_password', Input::get('token'), Input::get('email'));

        $success = Lang::get('account.success.password.reseted');
        return Redirect::to('/')->with('flash.notice.success', $success);
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
