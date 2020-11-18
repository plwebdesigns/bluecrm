<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        Auth::logout();
        Cookie::queue(Cookie::forget('token'));

        return redirect('login')->withCookie(Cookie('token', ""));
    }
}
