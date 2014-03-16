<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Password Reset</h2>

        <div>
            To reset your password, please click here:
            {{ URL::route('password_reset', ['token' => $token, 'email' => $user['email']]) }}.
        </div>
        <div id="disclaimer">

        </div>
    </body>
</html>
