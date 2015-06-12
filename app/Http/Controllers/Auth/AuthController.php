<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use App\Validators\PhoneValidator;

/**
 * Description of AuthController
 *
 * @author James
 */
class AuthController extends Controller {

    public function getRegister() {
        $user = new User();
        return view('auth.register', compact('user'));
    }

    public function postRegister() {
        $data = \Input::all();
        $rules = array(
            'email' => 'required|email|not_in:users,email',
            'phone_number' => 'kmobile',
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => 'required|confirmed|min:6'
        );

        $validator = \Validator::make($data, $rules, PhoneValidator::message());
        $user = new User();
        $this->map($data, $user);

        if ($validator->fails()) {
            \Session::flash('error', 'Please Correct the Highlighted Errors');
            return view('auth.register', compact('user'))->withErrors($validator->messages());
        }

        $user->phone = '+254' . substr($data['phone_number'], 1);
        $user->password = \Hash::make($data['password']);
        $user->save();

        //send mail

        \Session::flash('success', 'New User Successfuly Created');
        return \Redirect::action('Auth\AuthController@getRegister');
    }

    public function getLogin() {
        return view('auth.login');
    }

    public function postLogin() {
        $data = \Input::all();
        $rules = array(
            'email' => 'required|email',
            'password' => 'required'
        );

        $validator = \Validator::make($data, $rules);
        if ($validator->fails()) {
            \Session::flash('error', 'Correct the higlighted Errors');
            $email = \Input::has('email') ? $data['email'] : null;
            return view('auth.login', compact('email'))->withErrors($validator->messages());
        }

        $credentials = \Input::only('email', 'password');
        if (\Auth::attempt($credentials, \Input::has('remember'))) {
            return \Redirect::intended(\URL::action('DashBoardController@getIndex'));
        }

        \Session::flash('error', 'Invalid User ID/Password Combination ');
        $email = \Input::has('email') ? $data['email'] : null;
        return view('auth.login', compact('email'));
    }

    public function getLogout() {
        \Auth::logout();
        return \Redirect::to('auth/login');
    }

    private function map(array $data, User $user) {
        if (array_key_exists('email', $data)) {
            $user->email = $data['email'];
        }

        if (array_key_exists('phone_number', $data)) {
            $user->phone = $data['phone_number'];
        }

        if (array_key_exists('first_name', $data)) {
            $user->first_name = $data['first_name'];
        }

        if (array_key_exists('last_name', $data)) {
            $user->last_name = $data['last_name'];
        }
    }

}
