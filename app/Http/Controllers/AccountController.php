<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Description of AccountController
 *
 * @author jameskmb
 */
class AccountController extends Controller {

    public function getIndex() {
        $user = \Auth::user();
        return view('account.index', compact('user'));
    }

    public function postIndex() {
        $rules = array(
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'password' => 'confirmed|min:6'
        );
        $data = \Input::all();
        $user = clone \Auth::user();
        UserController::map($data, $user);
        $validator = \Validator::make($data, $rules);
        if ($validator->fails()) {
            \Session::flash('error', 'Correct The Higlighted Errors');
            return view('account.index', compact('user'))->withErrors($validator->messages());
        }

        $duplicate = User::where('email', '=', $data['email'])
                ->where('id', '<>', \Auth::user()->id)
                ->first();
        if (!is_null($duplicate)) {
            \Session::flash('error', 'There already exists a user with that email');
            return view('account.index', compact('user'));
        }

        if (strpos($data['phone_number'], '+254') === false) {
            $user->phone = '+254' . substr($data['phone_number'], 1);
        }

        if (trim($data['password'])) {
            $user->password = \Hash::make($data['password']);
        }

        $user->save();
        \Session::flash('success', 'Your Account Has been Successfuly Updated');
        return \Redirect::action('AccountController@getIndex');
    }
    
    public function getImage() {
        return $this->getIndex();
    }

    public function postImage() {
       
        //recheck uploaded file
        $file = \Input::file('avatar');
        if (is_null($file) || !$file->isValid()) {
            \Session::flash('error', 'Please Select the image to upload as your profile picture');
            return $this->getIndex();
        }

        //save data
        try {
            $name = $this->saveImage($file);
        } catch (\Exception $ex) {
            \Session::flash('error', 'Invalid Image. Please try again or Upload another image');
            return $this->getIndex();
        }

        $image = Image::create(array(
                    'filename' => $name,
                    'user_id' => \Auth::user()->id,
                    'description' => 'Profile Image'
        ));
        
        $user = \Auth::user();
        $user->image_id = $image->id;
        $user->save();
        \Session::flash('success', 'Your Profile Picture has been successfuly updated');
        return \Redirect::action('AccountController@getIndex');
    }
    
    public function getRemoveImage() {
        $user = \Auth::user();
        $user->image_id = null;
        $user->save();
        return \Redirect::action('AccountController@getIndex');
    }

    /**
     * Save an uploaded image
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @return string filename
     */
    private function saveImage(UploadedFile $file) {
        $location = public_path().'/uploads/images/';
        $imageFile = new \abeautifulsite\SimpleImage($file->getRealPath());
        $imageInfo = $imageFile->get_original_info();

        //construct file name
        $name = sprintf('%s.%s', time(), $imageInfo['format']);
        if (file_exists($location . $name)) {
            $name = sprintf('%s.%s', sha1(time()), $imageInfo['format']);
        }

        $imageFile->best_fit(Image::CROP_WIDTH, Image::CROP_HEIGHT)
                ->save($location . 'crop_' . $name);
        $file->move($location, $name);
        return $name;
    }

}
