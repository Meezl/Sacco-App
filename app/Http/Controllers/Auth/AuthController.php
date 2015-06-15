<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use \App\Http\Controllers\MessageHelper;
use App\Models\Message;
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
            if (\Auth::user()->is_locked) {
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

    public function getReset() {
        return view('auth.reset');
    }

    public function postReset() {
        $rules = array(
            'email' => 'required|email'
        );
        $data = \Input::all();
        $validator = \Validator::make($data, $rules);
        if ($validator->fails()) {
            \Session::flash('error', 'Please correct your errors first');
            return view('auth.reset')->withErrors($validator->messages());
        }

        $user = User::where('email', '=', $data['email'])->first();
        if ($user) {
            //generate random password            
            $pass = substr(trim(sha1(time())), 0, 8);
            $user->password = \Hash::make($pass);
            $user->save();
            $text = (string) view('sms.reset', compact('pass'));
            try {
                $status = MessageHelper::sendRaw($user->phone, $text);
                $msg = new Message();
                $msg->text = $text;
                $msg->user_id = $user->id;
                MessageHelper::map($status, $msg);
                $msg->deleted_at = date('Y-m-d H:i:s'); //hide
                $msg->save();
            } catch (\AfricasTalkingGatewayException $ex) {
                \Log::error($ex);
                \Debugbar::info(compact('ex'));
            }
            \Session::flash('success', 'If you are in our database, Your Password has been Successfuly reset. Check your inbox');
            return \Redirect::action('Auth\AuthController@getLogin');
        }
    }

}
