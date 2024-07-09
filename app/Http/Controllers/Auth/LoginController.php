<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ], [
            'username.required' => 'Nomor Induk Mahasiswa/Email Tidak Boleh Kosong.',
            'password.required' => 'Password Tidak Boleh Kosong.',
        ]);

        $user = User::where('email', $request->username)->orWhere('nim', $request->username)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                Auth::login($user);
                return redirect()->route('dashboard');
            } else {
                return redirect()->route('login')->with('message', 'Nim/Email/Password Salah.');
            }
        } else {
            return redirect()->route('login')->with('message', 'Akun Tidak Terdaftar.');
        }
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect()->route('home');
    }
}
