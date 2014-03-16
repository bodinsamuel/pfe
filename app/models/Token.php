<?php

class Token extends Eloquent {

    protected $table = 'tokens';
    public $timestamps = false;

    const TOKEN_LIFE = 900; # 15minutes
    const TOKEN_EXPIRED = -1;
    const TOKEN_USED = FALSE;

    public static function get($type, $token, $email)
    {
        return Token::where('type', '=', $type)
                    ->where('token', '=', $token)
                    ->where('email', '=', $email)->first();
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

    public static function ensure($type)
    {
        // Verify paramater
        $email = Input::get('email');
        $token = Input::get('token');
        if ($token === NULL || $email === NULL)
            return FALSE;

        // Check if token really exist
        $exist = Token::get($type, $token, $email);
        if ($exist === NULL)
            return FALSE;

        if ($exist->date_used !== '0000-00-00 00:00:00')
            return self::TOKEN_USED;

        if (abs(strtotime($exist->date_created) - time()) > self::TOKEN_LIFE)
            return self::TOKEN_EXPIRED;

        return TRUE;
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
