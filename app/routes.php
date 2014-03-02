<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'HomeController@run');

// Post
Route::get('/renting/{id_post}/{title}', 'PostController@get');
Route::get('/selling/{id_post}/{title}', 'PostController@get');
Route::get('/renting/business/{id_post}/{title}', 'PostController@get');
Route::get('/selling/business/{id_post}/{title}', 'PostController@get');
    // Action
    Route::get('/post/new/', 'PostController@new');
    Route::post('/post', 'PostController@create');
    Route::post('/post/{id_post}', 'PostController@edit');
    Route::delete('/post/{id_post}', 'PostController@delete');

// Account
Route::get('/account', ['as' => 'account', 'before' => 'auth', 'uses' => 'AccountController@get_Dashboard']);
    // Register
    Route::get('/register', ['before' => 'guest', 'uses' => 'AccountController@get_Register']);
    Route::post('/register', ['before' => 'csrf', 'uses' => 'AccountController@post_Register']);

    // Login
    Route::get('/login', ['as' => 'login', 'before' => 'guest', 'uses' => 'AccountController@get_Login']);
    Route::post('/login', ['before' => 'csrf', 'uses' => 'AccountController@post_Login']);
    Route::get('/logout', ['as' => 'logout', 'before' => 'auth', 'uses' => 'AccountController@get_Logout']);

    // Deactivate
    Route::get('/account/deactivate', ['as' => 'account_deactivate', 'before' => 'auth', 'uses' => 'AccountController@get_Deactivate']);
    Route::post('/account/deactivate', ['before' => 'auth', 'uses' => 'AccountController@post_Deactivate']);

    // Password
    Route::get('/password/forgotten', ['as' => 'password_forgot', 'before' => 'guest', 'uses' => 'AccountController@get_ForgotPassword']);
    Route::post('/password/forgotten', ['before' => 'guest', 'uses' => 'AccountController@post_ForgotPassword']);
    Route::get('/password/reset', ['as' => 'password_reset', 'before' => 'guest', 'uses' => 'AccountController@get_ResetPassword']);
    Route::post('/password/reset', ['uses' => 'AccountController@post_ResetPassword']);

    // Edit
    Route::get('/account/edit', ['as' => 'account_edit', 'before' => 'auth', 'uses' => 'AccountController@get_Edit']);
    Route::post('/account/edit', ['before' => 'auth', 'uses' => 'AccountController@post_Edit']);

    // Other
    Route::get('/account/alert', ['as' => 'account_alert', 'before' => 'auth', 'uses' =>  'AccountController@get_Alert']);
    Route::get('/account/favorite', ['as' => 'account_favorite', 'before' => 'auth', 'uses' =>  'AccountController@get_Favorite']);
    Route::get('/account/address', ['as' => 'account_address', 'before' => 'auth', 'uses' =>  'AccountController@get_Address']);

// Agencies
Route::get('/agency/{id_agency}/{title}', 'AgencyController@get');
Route::get('/agency/new', 'AgencyController@new');
Route::post('/agency', 'AgencyController@create');
Route::post('/agency/{id_agency}', 'AgencyController@edit');
Route::delete('/agency/{id_agency}', 'AgencyController@delete');

// Search
Route::get('/search/{id_search?}', 'SearchController@run');
Route::post('/search/{id_search?}', 'SearchController@save');
Route::delete('/search/{id_search}', 'SearchController@delete');

// Favorites
Route::get('/favorite/{id_favorite?}', 'FavoriteController@get');
Route::post('/favorite/{id_favorite}', 'FavoriteController@save');
Route::delete('/favorite/{id_favorite}', 'FavoriteController@delete');

// Alerts
Route::get('/alert/{id_alert?}', 'AlertController@get');
Route::post('/alert/{id_alert?}', 'AlertController@save');
Route::delete('/alert/{id_alert}', 'AlertController@delete');



// **********************************************
// JSON API



// **********************************************
// On matche
Route::matched(function() {
    $method = $controller = '';
    $route = Route::currentRouteAction();
    $pos = strpos($route, '@');
    if ($pos !== FALSE)
    {
        $route = Str::parseCallback($route, null);
        if (is_array($route))
        {
            $method = $route[1];
            $controller = str_replace('Controller', '', $route[0]);
        }
    }

    View::share('__current_method', $method);
    View::share('__current_controller', $controller);
});


