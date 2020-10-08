<?php

namespace App\Http\Controllers\InvAuth;

use \Illuminate\Http\Request;

class LoginController extends \App\Http\Controllers\Auth\LoginController
{

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if($user->active == 0){
            $this->guard()->logout();
            $request->session()->invalidate();
            return redirect()->route('register.not.confirmed');
        }
    }
}
