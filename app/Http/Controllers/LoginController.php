<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login() {
        return view('login');
    }

    public function loginuser(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        return redirect('/')->with('toast_success', 'Login Berhasil');
    }

    return redirect('login')->withErrors([
        'login' => 'Email atau password salah.',
    ])->withInput();
}

    public function logout() {
        Auth::logout();
        return redirect('login');
    }
    // public function register() {
    //     return view('register');
    // }
}
