<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Database\Connectors\MySqlConnector;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller {
	/**
	 * Handle an authentication attempt.
	 *
	 * @param Request $request
	 *
	 * @return RedirectResponse
     */
	public function authenticate(Request $request)
    {
		$credentials = $request->only('email', 'password');

		if (Auth::attempt($credentials)) {
			$user = Auth::user();
			$token = $user->api_token;

			// Authentication passed...
            return redirect()->intended()->withCookie(cookie('token', $token, null, null, null, null, false));
//			return redirect('/')->withCookie(cookie('token', $token, null, null, null, null, false));
		} else {
			return redirect('/login');
		}
	}
}
