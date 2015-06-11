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

/**
Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

**/

Route::get('/', function() {
    
   return view('login'); 
});

Route::get('/home', function() {
   return view('dashboard');
});

Route::get('/users', function() {
   return view('users');
});

Route::get('campaigns', function() {
 return view('campaigns');
});

Route::get('messages', function() {
   return view('messages');
});