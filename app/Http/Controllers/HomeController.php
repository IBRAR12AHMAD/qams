<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\ForgotPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;


class HomeController extends Controller
{

    public function index()
    {
        return view('auth.userlogin');
    }

    public function home(Request $request)
    {
        return view('home');
    }

     public function userLogin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()
                ->route('home')
                ->with('success', 'Welcome, ' . Auth::user()->name . '!');
        }

        return redirect()
            ->route('indexlogin')
            ->withErrors(['password' => 'These credentials do not match our records.'])
            ->withInput();
    }

    // public function userLogin(Request $request)
    // {
    //     $credentials = $request->validate([
    //         'email' => ['required', 'email'],
    //         'password' => ['required'],
    //     ]);

    //     if (Auth::attempt($credentials)) {
    //         if (Auth::check()) {
    //             $user = Auth::user();
    //             return redirect()->route('home')->with('success', 'Welcome, ' . $user->name . '! You have logged in successfully!');
    //         } else {
    //             return redirect()->route('logoutuser')->with('error', 'User authentication failed.');
    //         }
    //     } else {
    //         return redirect()->route('indexlogin')
    //             ->withErrors(['password' => 'These credentials do not match our records.'])
    //             ->withInput();
    //     }
    // }

    public function logoutUser(Request $request) {

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

}
