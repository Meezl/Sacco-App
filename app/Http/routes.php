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
});


Route::controller('auth', 'Auth\AuthController');

Route::get('test', function() {
    $gateway = new AfricasTalkingGateway(config('sms.api_username'), config('sms.api_key'));
    $recepient = '+254705813955,+254734741807';
    $message = 'Testing Server config';
    $from = config('sms.system_number');
    try {
        $results = $gateway->sendMessage($recepient, $message, $from);
        foreach ($results as $result) {
            echo ' Number: ' . $result->number;
            echo ' Status: ' . $result->status;
            echo ' MessageId: ' . $result->messageId;
            echo ' Cost: ' . $result->cost . '\n';
        }
        echo '<br />success';
    } catch (AfricasTalkingGatewayException $e) {
        echo $e->getMessage();
    }
    return 'done';
});
