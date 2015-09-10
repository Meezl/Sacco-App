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

//register custom kenyan mobile number validator
Validator::extend('kmobile', 'App\Validators\PhoneValidator@validate');



Route::get('callback', function() {
    return view('callback');
});

Route::post('callback', 'MessageController@postHandleCallback');

//Route::group(['prefix' => 'dashboard', 'middleware' => 'auth'], function() {

Route::group(['middleware' => 'auth'], function() {

    Route::get('/', 'DashBoardController@getIndex');

    Route::post('/new-meeting', 'DashBoardController@postNewMeeting');

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
    return strlen('The Third and possibly the last option possible nice one people The Third and ');
});

Route::get('another', function() {

    $campaign = App\Models\Campaign::where('id_string', 'D52')->firstOrFail();
    $statsGenerator = new App\Commands\GenerateStats($campaign);
    
    $pdf = new \App\Models\Report(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->loadDefaults($campaign, $statsGenerator->handle());
    $pdf->intro();
    $pdf->Ln();
    $pdf->renderQuestion();
    $pdf->Ln();
    $pdf->overview();
    $pdf->AddPage();
    $pdf->renderMessages();

// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
    $pdf->Output('example_001.pdf', 'I');
});
