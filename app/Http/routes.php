<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */

//register custom validators
Validator::extend('kmobile', 'App\Validators\PhoneValidator@validate');


Route::get('/', function() {
    return redirect('dashboard');
});


Route::group(['prefix' => 'dashboard', 'middleware' => 'auth'], function() {

    Route::get('/', 'DashBoardController@getIndex');

    Route::controller('user', 'UserController');
    
    Route::controller('contacts', 'ContactController');
});


Route::controller('auth/login', 'Auth\AuthController');

Route::get('/users', function() {
    return view('users');
});

Route::get('campaigns', function() {
    return view('campaigns');
});

Route::get('messages', function() {
    return view('messages');
});
