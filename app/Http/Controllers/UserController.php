<?php

namespace App\Http\Controllers;

use App\Validators\PhoneValidator;
use App\User;
use App\Models\Message;

/**
 * Description of UserController
 *
 * @author jameskmb
 */
class UserController extends Controller {

    const USERS_PER_PAGE = 10;

    public function getIndex() {
        $users = User::where('id', '<>', \Auth::user()->id)
                ->orderBy('created_at', 'desc')
                ->paginate(self::USERS_PER_PAGE)
                ->setPath(\URL::current());
        return view('users.index', compact('users'));
    }

    public function getBlock($id) {
        $user = User::find($id);
        $this->show404Unless($user);

        if ($user->id == \Auth::user()->id) {
            Session::flash('error', 'Illegal Action. You cannot block yourself');
        } else {
            $user->is_locked = 1;
            $user->save();
            \Session::flash('success', $user->getFullName() . ' Successfuly Blocked');
        }


        return \Redirect::action('UserController@getIndex');
    }

    public function getUnblock($id) {
        $user = User::find($id);
        $this->show404Unless($user);

        if ($user->id == \Auth::user()->id) {
            Session::flash('error', 'Illegal Action. You cannot unblock yourself');
        } else {
            $user->is_locked = 0;
            $user->save();
            \Session::flash('success', $user->getFullName() . ' Successfuly Un-Blocked');
        }

        return \Redirect::action('UserController@getIndex');
    }

    public function getRegister() {
        if(!\Auth::user()->is_admin) {
            \Session::flash('error', 'Whoops! Looks like you do not have the permission to create new users');
            return \Redirect::action('UserController@getIndex');
        }
        $user = new User();
        return view('users.register', compact('user'));
    }

    public function postRegister() {
        $data = \Input::all();
        $rules = array(
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|kmobile',
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => 'required|confirmed|min:6'
        );

        $validator = \Validator::make($data, $rules, PhoneValidator::message());
        $user = new User();
        self::map($data, $user);

        if ($validator->fails()) {
            \Session::flash('error', 'Please Correct the Highlighted Errors');
            return view('users.register', compact('user'))->withErrors($validator->messages());
        }
        if (strpos($data['phone_number'], '+254') === false) {
            $user->phone = '+254' . substr($data['phone_number'], 1);
        }
        
        
        $user->password = \Hash::make($data['password']);
        $user->save();
        
        //send notification
        $this->sendRegNotification($user, $data['password']);

        \Session::flash('success', 'New User Successfuly Created');
        return \Redirect::action('UserController@getRegister');
    }

    private function sendRegNotification(User $user, $pass) {
        try {
            $username = $user->email;
            $text = (string) \View::make('sms.registration', compact('username', 'pass'));
            $status = MessageHelper::sendRaw($user->phone, $text);
        } catch (Exception $ex) {
            \Session::flash('info', 'Error (' . $ex->getMessage() . ') while notifiying the user about his registration. Send login credentials to him/her manualy');
        }
        if (count($status)) {
            $msg = new Message();
            $msg->text = $text;
            MessageHelper::map($status, $msg);
            $msg->deleted_at = date('Y-m-d H:i:s'); //hide
            $msg->save();
        }
    }

    public static function map(array $data, User $user) {
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
