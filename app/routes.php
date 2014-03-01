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
Route::get('/account', 'AccountController@get_Dashboard');
    // Register
    Route::get('/register', 'AccountController@get_Register');
    Route::post('/register', ['before' => 'csrf', 'uses' => 'AccountController@post_Register']);

    // Login
    Route::get('/login', 'AccountController@get_Login');
    Route::post('/login', 'AccountController@post_Login');
    Route::get('/logout', 'AccountController@get_Logout');

    // Deactivate
    Route::get('/account/deactivate', 'AccountController@get_Deactivate');
    Route::post('/account/deactivate', 'AccountController@post_Deactivate');

    // Reset Password
    Route::get('/account/reset_password', 'AccountController@get_ResetPassword');
    Route::post('/account/reset_password', 'AccountController@post_ResetPassword');

    // Edit
    Route::get('/account/edit', 'AccountController@get_Edit');
    Route::post('/account/edit', 'AccountController@post_Edit');

    // Other
    Route::get('/account/alert', 'AccountController@get_Alert');


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


