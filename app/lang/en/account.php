<?php

return [

    'success' => [
        'register' => 'You have succesfully register, please validate your account by clicking the link on the email you just recieved',

        'login' => 'Welcome back !',

        'logout' => 'You succesfully logout, see you soon',

        'password' => [
            'send_forgot' => 'An email has been sent to you to get your new password',
            'reseted' => 'Your password has been reseted correctly'
        ],

        'validation' => [
            'sent' => 'An email has been sent to you with the link.',
            'done' => 'You have succesfully validated your account. Enjoy :)'
        ]
    ],

    'error' => [
        'email_not_verified' => 'Please validate your email before login. <br> Never recieved the mail? <a href="/account/send_validation?email=:mail">Send me another !</a>',
        'login' => 'Your username/password combination was incorrect',

        'validation' => [
            'expired' => 'The link is expired  <a href="/account/send_validation?email=:mail">Send me another !</a>'
        ]
    ],
];
