<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\MailResetPasswordNotification;

class User extends AuthenticatableForUser implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name','last_name', 'username',  'email', 'password', 'avatar', 'phone', 'gender', 'type', 'status', 'address',
    ];
    public function sendPasswordResetNotification($token){

        $this->notify(new MailResetPasswordNotification($token));
    }
    public function sendEmailVerificationNotification(){

        $this->notify(new VerifyEmail);
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
