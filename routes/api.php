<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::prefix('user')->group(function () {
    Route::post('register', 'UserController@register')->name('register');
    Route::post('login', 'UserController@login')->name('login');
 
    Route::post('email/verify/{id}', 'VerificationController@verify')->name('verification.verify');
    Route::post('email/resend', 'VerificationController@resend')->name('verification.resend');
    Route::post('reset-password', 'UserController@sendPasswordResetLink');
    Route::post('reset/password', 'UserController@callResetPassword');

    Route::get('donate-paypal', 'DonationController@donatePaypal');
    Route::get('paypal-check', 'DonationController@checkPaypal');

});
Route::group(['middleware' => ['jwt.verify'], 'prefix' => 'user'], function(){
    Route::get('profile', 'UserController@get');
    Route::post('logout', 'UserController@logout');
    Route::put('update', 'UserController@update');
    Route::put('update-password', 'UserController@updatePassword');


    Route::get('messages/{id}', 'MessageController@get');
    Route::get('conversations', 'MessageController@conversations');
    Route::post('message-create', 'MessageController@create');

    Route::get('chats', 'ChatController@get');
    Route::post('chat-create', 'ChatController@create');
    Route::put('chat-update', 'ChatController@update');

    Route::get('friends', 'FriendController@get');
    Route::post('friend-create', 'FriendController@create');
    Route::put('friend-update', 'FriendController@update');

    Route::get('members/{id}', 'MemberController@get');
    Route::post('member-create', 'MemberController@create');
    Route::put('member-update', 'MemberController@update');

    Route::get('donations', 'DonationController@get');
  
});
