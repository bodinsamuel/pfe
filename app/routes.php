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
Route::get('/location/particuler/{id_post}/{title}', 'PostController@renting');
Route::get('/vente/particuler/{id_post}/{title}', 'PostController@selling');
    // Action
    Route::get('/post/new/', 'PostController@new');
    Route::post('/post', 'PostController@create');
    Route::post('/post/{id_post}', 'PostController@edit');
    Route::delete('/post/{id_post}', 'PostController@delete');

// Account
Route::get('/account', 'AccountController@run');
Route::get('/register', 'AccountController@login');
Route::get('/login', 'AccountController@login');
Route::get('/logout', 'AccountController@logout');
Route::get('/account/deactivate', 'AccountController@deactivate');
Route::get('/account/reset_password', 'AccountController@reset_password');
Route::get('/account/alerts', 'AccountController@alerts');
Route::get('/account/edit', 'AccountController@edit');

// Agencies
Route::get('/agence/{id_agency}/{title}', 'AgencyController@get');
Route::get('/agence/new', 'AgencyController@new');
Route::post('/agence', 'AgencyController@create');
Route::post('/agence/{id_agency}', 'AgencyController@edit');
Route::delete('/agence/{id_agency}', 'AgencyController@delete');

// Search
Route::get('/search', 'SearchController@run');
Route::post('/search/save', 'SearchController@save');
Route::get('/search/{id_search}', 'SearchController@get');
Route::post('/search/edit/{id_search}', 'SearchController@edit');
Route::delete('/search/{id_search}', 'SearchController@delete');

// Favorites
Route::get('/favorite/{id_favorite?}', 'FavoriteController@get');
Route::post('/favorite/{id_favorite}', 'FavoriteController@save');
Route::delete('/favorite/{id_favorite}', 'FavoriteController@delete');

// Alerts
Route::get('/alert/{id_alert?}', 'AlertController@get');
Route::post('/alert', 'AlertController@create');
Route::post('/alert/{id_alert}', 'AlertController@edit');
Route::delete('/alert/{id_alert}', 'AlertController@delete');


