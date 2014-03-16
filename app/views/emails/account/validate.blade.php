<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Activate Account</h2>

        <div>
            To activate your account please clik on this link
            {{ URL::route('account_validate', ['token' => $token, 'email' => $user['email']]) }}.
        </div>
        <div id="disclaimer">

        </div>
    </body>
</html>
