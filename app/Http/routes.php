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

Route::post('callback', 'MessageController@postHandleCallback');


Route::group(['prefix' => 'dashboard', 'middleware' => 'auth'], function() {

    Route::get('/', 'DashBoardController@getIndex');

    Route::controller('user', 'UserController');

    Route::controller('contacts', 'ContactController');

    Route::controller('account', 'AccountController');

    Route::controller('category', 'CategoryController');

    Route::controller('campaign', 'CampaignController');

    Route::controller('message', 'MessageController');

    Route::controller('stats', 'StatsController');
});


Route::controller('auth', 'Auth\AuthController');

Route::get('test', function() {
    $campaign = App\Models\Campaign::find(12);
    $options = explode("\n", $answers);
    $result = [];
    foreach ($options as $o) {
        $temp = explode(':', $o);
        $result[] = array(
            'key' => $temp[0],
            'val' => $temp[1]
        );
    }
    return $result;
    Debugbar::info(compact('result'));
    return 'done';
});
