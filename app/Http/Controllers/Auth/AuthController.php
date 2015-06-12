<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;

/**
 * Description of AuthController
 *
 * @author James
 */
class AuthController extends Controller {


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
            if(\Auth::user()->is_locked) {
                \Session::flash('error', 'You are locked from accessing you account. Contact Admin for more details');
                return $this->getLogout();
            }
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
}
