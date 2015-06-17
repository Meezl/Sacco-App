<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

    use Authenticatable,
        CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * User Profile Photo
     * @var App\Model\Image
     */
    private $avatar = false;

    public function getFullName() {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function images() {
        return $this->hasMany('App\Models\Image');
    }

    /**
     * Get User Profile Image
     * @return App\Models\Image profile image
     */
    public function getAvatar() {
        if (!$this->image_id) {
            return null;
        }
        if ($this->avatar === false) {
            $this->avatar = $this->images()
                    ->where('id', '=', $this->image_id)
                    ->first();
        }
        return $this->avatar;
    }

}
