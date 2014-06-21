<?php namespace Custom\User;

class Dashboard
{
    public static function updateInformations($inputs)
    {
        $return = ['errors' => []];
        $return['inputs'] = &$inputs;

        $inputs = array_fill_base([
            'email', 'first_name', 'last_name', 'phone_mobile'
        ], $inputs);
        $inputs['id_user'] = \User::getIdOrZero();

        // Validate all fields before insert
        $validation = self::validateInformations($inputs);

        if ($validation->fails())
        {
            $return['failed'] = TRUE;
            $return['errors'] = $validation->messages()->toArray();
            return $return;
        }

        $query = 'UPDATE users
                     SET email = :email,
                         first_name = :first_name,
                         last_name = :last_name,
                         phone_mobile = :phone_mobile
                   WHERE id_user = :id_user
                   LIMIT 1';

        $update = \DB::statement($query, $inputs);
        if ($update === FALSE)
            return -1;

        return $return;
    }

    public static function validateInformations($inputs)
    {
        return \Validator::make(
            $inputs, [
                'id_user' => 'required|integer',
                'email' => 'required|email|unique:users',
                'first_name' => '',
                'last_name' => '',
                'phone_mobile' => 'integer'
            ]
        );
    }

    public static function updatePassword($inputs)
    {
        $return = ['errors' => []];
        $return['inputs'] = &$inputs;

        $inputs = array_fill_base([
            'current_pwd', 'new_pwd', 'new_pwd_confirmation'
        ], $inputs);
        $inputs['id_user'] = \User::getIdOrZero();

        // Validate all fields before insert
        $validation = self::validatePassword($inputs);

        if ($validation->fails())
        {
            $return['failed'] = TRUE;
            $return['errors'] = $validation->messages()->toArray();
            return $return;
        }

        $auth = \Custom\Account::auth([
            'email' => \Custom\Account::user()->email,
            'password' => $inputs['current_pwd']
        ]);
        if ($auth === FALSE)
        {

            $return['failed'] = TRUE;
            $return['errors'] = ['current_pwd' => 'Wrong password'];
            return $return;
        }

        $query = 'UPDATE users
                     SET password = :password
                   WHERE id_user = :id_user
                   LIMIT 1';

        $update = \DB::statement($query, [
            'password' => \Hash::make($inputs['new_pwd']),
            'id_user' => $inputs['id_user']
        ]);
        if ($update === FALSE)
            return -1;

        return $return;
    }

    public static function validatePassword($inputs)
    {
        return \Validator::make(
            $inputs, [
                'id_user' => 'required|integer',
                'current_pwd' => 'required|min:8',
                'new_pwd' => 'required|min:8|confirmed',
            ]
        );
    }
}
