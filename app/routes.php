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
// SUBDOMAIN FIRST
Route::group(['domain' => 'media.pfe.dev'], function()
{
    Route::controller('/', 'MediaServer_Get');
});


// HOME PAGE
Route::get('/', 'HomeController@run');

// Post
Route::get('/post/{id_post}/{title}', 'PostController@get_one');
Route::get('/renting/{id_post}/{title}', 'PostController@get_one');
Route::get('/selling/{id_post}/{title}', 'PostController@get_one');
Route::get('/renting/business/{id_post}/{title}', 'PostController@get_one');
Route::get('/selling/business/{id_post}/{title}', 'PostController@get_one');

    // Action
    Route::group(['before' => 'auth'], function()
    {
        Route::get('/post/create/', ['as' => 'post_create', 'uses' => 'PostController@get_create']);
        Route::post('/post/create/', 'PostController@post_create');
        Route::get('/post/edit/{id_post}', ['as' => 'post_edit', 'uses' => 'PostController@get_edit']);
        Route::post('/post/edit/{id_post}', 'PostController@post_edit');
        Route::get('/post/delete/{id_post}',['as' => 'post_delete', 'uses' => 'PostController@get_delete']);
        Route::delete('/post/delete/{id_post}', 'PostController@delete_delete');
    });

// Account
Route::get('/account', ['as' => 'account', 'before' => 'auth', 'uses' => 'AccountController@get_Dashboard']);
    // Register
    Route::get('/register', ['before' => 'guest', 'uses' => 'AccountController@get_Register']);
    Route::post('/register', ['before' => 'csrf', 'uses' => 'AccountController@post_Register']);

    // Login
    Route::get('/login', ['as' => 'login', 'before' => 'guest', 'uses' => 'AccountController@get_Login']);
    Route::post('/login', ['before' => 'csrf', 'uses' => 'AccountController@post_Login']);
    Route::get('/logout', ['as' => 'logout', 'before' => 'auth', 'uses' => 'AccountController@get_Logout']);

    // Active / Deactivate
    Route::get('/account/validate', ['as' => 'account_validate', 'before' => 'guest', 'uses' => 'AccountController@get_validate']);
    Route::get('/account/send_validation', [ 'before' => 'guest', 'uses' => 'AccountController@get_sendValidation']);
    Route::get('/account/deactivate', ['as' => 'account_deactivate', 'before' => 'auth', 'uses' => 'AccountController@post_Deactivate']);
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
Route::get('/search/', 'SearchController@get_Run');
Route::get('/search/{id_search?}', 'SearchController@get_One');
Route::post('/search/{id_search?}', 'SearchController@post_Save');
Route::delete('/search/{id_search}', 'SearchController@delete_Delete');

// Favorites
Route::get('/favorite/{id_favorite?}', 'FavoriteController@get');
Route::post('/favorite/{id_favorite}', 'FavoriteController@save');
Route::delete('/favorite/{id_favorite}', 'FavoriteController@delete');

// Alerts
Route::get('/alert/{id_alert?}', 'AlertController@get');
Route::post('/alert/{id_alert?}', 'AlertController@save');
Route::delete('/alert/{id_alert}', 'AlertController@delete');


Route::controller('sandbox', 'SandboxController');



// **********************************************
// JSON API
Route::group(['prefix' => 'api/v1'], function()
{
    Route::controller('autocomplete', 'ApiV1\Autocomplete');
    Route::controller('search', 'ApiV1\Search');
});


// **********************************************
// Services
Route::group(['prefix' => 'services'], function()
{
    Route::controller('seloger', 'Seloger_Bot');
    Route::controller('pap', 'Pap_Bot');
});

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


