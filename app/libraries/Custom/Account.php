<?php namespace Custom;

class Account
{
    const LOGIN_SUCCESS = 1;
    const LOGIN_MISSING_CRED = -1;
    const LOGIN_WRONG_CRED = -2;
    const LOGIN_NOT_ACTIVATED = -3;

    public static function login(array $inputs)
    {
        if (!isset($inputs['email']) || !isset($inputs['password']))
            return self::LOGIN_MISSING_CRED;

        // Try Logged
        if (Account::auth($inputs))
        {
            $user = Account::user();
            if ($user->status === 1)
            {
                \Session::put('id_user', $user->id_user);
                \Session::put('id_acl', $user->id_acl);
                \Session::put('acl_name', $user->acl_name);

                return self::LOGIN_SUCCESS;
            }
            else
            {
                \Auth::logout();
                return self::LOGIN_NOT_ACTIVATED;
            }
        }
        else
        {
            return self::LOGIN_WRONG_CRED;
        }
    }

    public static function auth(array $inputs)
    {
        $query = 'SELECT id_user, password
                    FROM users
                   WHERE email = :email';

        $auth = \DB::select($query, ['email' => $inputs['email']]);
        if (empty($auth))
            return FALSE;

        // Pasword verify is a php5 function
        $veryfied = password_verify($inputs['password'], $auth[0]->password);
        if ($veryfied !== TRUE)
            return FALSE;

        \Session::put('id_user', $auth[0]->id_user);
        return TRUE;
    }

    public static function check()
    {
        return (bool)\Session::get('id_user');
    }

    public static function user($force = FALSE)
    {
        static $user;

        if ($user !== NULL && $force === FALSE)
            return $user;

        if (!Account::check())
            return [];

        $query = 'SELECT users.id_user,
                         email,
                         first_name,
                         last_name,
                         phone_mobile,
                         phone_office,
                         status,
                         date_created,
                         date_updated,
                         date_validated,

                         acl.id_acl,
                         acl.name As acl_name
                    FROM users
               LEFT JOIN users_has_acl
                         ON users_has_acl.id_user = users.id_user
               LEFT JOIN acl
                         ON acl.id_acl = users_has_acl.id_acl
                   WHERE users.id_user = ' . (int)\Session::get('id_user');

        $user = \DB::select($query);
        if (!empty($user))
            $user = $user[0];

        return $user;
    }
}
