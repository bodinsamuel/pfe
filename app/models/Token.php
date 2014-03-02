<?php

class Token extends Eloquent {

    protected $table = 'tokens';
    public $timestamps = false;

    public static function get($type, $token, $email)
    {
        return Token::where('type', '=', $type)
                    ->where('token', '=', $token)
                    ->where('email', '=', $email)
                     ->where('date_used', '=', '0000-00-00 00:00:00')->first();
    }

    public static function add($type, $token, $email)
    {
        $Token = new Token;
        $Token->type = $type;
        $Token->token = $token;
        $Token->email = $email;
        $Token->date_created = date('Y-m-d H:i:s');

        return $Token->save();
    }

    public static function exist($type, $token, $email)
    {
        return (bool)Token::get($type, $token, $email);
    }

    public static function confirm($type, $token, $email)
    {
        return DB::update(
          'UPDATE tokens
              SET date_used = NOW()
            WHERE type = ? AND
                  token = ? AND
                  email = ?',
            [$type, $token, $email]);
    }
}
