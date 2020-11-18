<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Database\Connectors\MySqlConnector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller {
	/**
	 * Handle an authentication attempt.
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Routing\Redirector
	 */
	public function authenticate(Request $request) {
		$credentials = $request->only('email', 'password');

		if (Auth::attempt($credentials)) {
			$user = Auth::user();
			$token = $user->api_token;

			// Authentication passed...
			return redirect('/')->withCookie(cookie('token', $token, null, null, null, null, false));
		} else {
			return redirect('/login');
		}
	}
}
