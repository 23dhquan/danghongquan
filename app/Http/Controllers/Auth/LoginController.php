<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.sign-in');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Lưu trữ thông tin người dùng trong session
           $user = Auth::user();
           if ($user->status == 1){
               return redirect()->intended('');
           }
           else{
               return back()->withErrors([
                   'email' => 'Tài khoản của bạn đang bị khóa',
               ]);
           }
        }

        return back()->withErrors([
            'email' => 'Sai thông tin đăng nhập',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/auth/login');
    }
}
