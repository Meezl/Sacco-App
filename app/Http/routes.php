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


Route::get('/', function() {
    return redirect('dashboard');
});


Route::group(['prefix' => 'dashboard', 'middleware' => 'auth'], function() {

    Route::get('/', 'DashBoardController@getIndex');

    Route::controller('user', 'Auth\AuthController');
});


Route::get('auth/login', 'Auth\AuthController@getLogin');

Route::post('auth/login', 'Auth\AuthController@postLogin');

Route::get('auth/logout', 'Auth\AuthController@getLogout');


Route::get('test-swift', function() {
    $message = Swift_Message::newInstance();
    $message->setTo('jameskmw48@gmail.com');
    $message->setFrom('jameskm48@mail.com', 'James Kamau');
    $message->setSubject('Hello World');
    $message->setBody('I love you');

    $mailer = Swift_Mailer::newInstance(Swift_MailTransport::newInstance());
    return $mailer->send($message) ? 'success' : 'failure';
});

Route::get('test-mailer', function() {
    $mail = new App\Mailer();
    $mail->setTo('jameskmw48@gmail.com');
    $mail->setFrom('devjameskm48@mail.com', 'James Kamau');
    $mail->setSubject('Hello WOrld');
    $mail->setMessage('<html></body>Just Want to know you.</body></html>');
    return $mail->send() ? 'Success' : 'Failure';
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
