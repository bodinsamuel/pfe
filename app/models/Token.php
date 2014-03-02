<?php

class Token extends Eloquent {

    protected $table = 'v1_tokens';
    public $timestamps = false;

    public static function get($type, $token, $email)
    {
        return Token::where('type', '=', $type)
                    ->where('token', '=', $token)
                    ->where('email', '=', $email);
    }

    public static function set($type, $token, $email)
    {
        $Token = new Token;
        $Token->type = $type;
        $Token->token = $token;
        $Token->email = $email;
        $Token->date_creation = date('Y-m-d H:i:s');
        $Token->used = 0;

        return $Token->save();
    }
}
