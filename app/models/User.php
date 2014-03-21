<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password'];

    protected $primaryKey = 'id_user';

    public $timestamps = false;

    const VALIDATED = 1;

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

    public function isValidated($email = NULL)
    {
        return ($this->status === User::VALIDATED) ? TRUE : FALSE;
    }

    public static function getIdOrZero()
    {
        return (\Auth::user() === NULL) ? 0 : Auth::user()->id_user;
    }

    /**
     * Validate registering
     * @param  array $input
     * @return array
     */
    public static function validateRegister($input = NULL)
    {
        $input = is_array($input) ? $input : Input::all();

        return Validator::make(
            $input, [
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8'
            ]
        );
    }

    /**
     * Validate password reset
     * @param  array $input
     * @return array
     */
    public static function validatePasswordReset($input = NULL)
    {
        $input = is_array($input) ? $input : Input::all();

        return Validator::make(
            $input, [
                'password' => 'required|min:8|confirmed',
                'password_confirmation' => 'required'
            ]
        );
    }
}
