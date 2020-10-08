<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Auth::routes();

//overriding default Auth Rote

Route::get('login', 'InvAuth\LoginController@showLoginForm')->name('login');
Route::post('login', 'InvAuth\LoginController@login');
Route::post('logout', 'InvAuth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register', 'InvAuth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'InvAuth\RegisterController@register');
Route::get('register/thanks', 'InvAuth\RegisterController@thanks')->name('register.thanks');
Route::get('register/confirm/{token}', 'InvAuth\RegisterController@activateAccount')->name('register.confirm');
Route::get('register/not-confirmed', 'InvAuth\RegisterController@notConfirmed')->name('register.not.confirmed');
Route::get('register/resend-activation-email', 'InvAuth\RegisterController@resendActivation')->name('register.resend.email');
Route::post('register/resend-activation-email', 'InvAuth\RegisterController@resendActivationEmail')->name('register.resend.activation.email');


// Password Reset Routes...
Route::get('password/reset', 'InvAuth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'InvAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'InvAuth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'InvAuth\ResetPasswordController@reset');


Route::get('/home', 'HomeController@index')->name('home');
