<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthControllerNojwt extends Controller
{
    public function login (Request $request){
        if ($request->isMethod('post')){
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            if (Auth::attempt($credentials)){
                $request->session()->regenerate();
                $jwt_token = JWTAuth::attempt($credentials);
                session(['jwt_token' => $jwt_token]);
                return redirect()->route('admin.dashboard');
            } else {
                return back()->withErrors([
                    'email' => 'The provided credentials do not match our records'
                ]);
            }
        }
        return view('auth.login');
    }

    public function logout(){
        if (Auth::check()){
            Auth::logout();
        }
        return view('auth.login');
    }

}
