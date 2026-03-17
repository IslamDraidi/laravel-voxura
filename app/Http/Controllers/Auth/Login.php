<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
<<<<<<< Updated upstream
=======
use Illuminate\Support\Facades\Auth;
>>>>>>> Stashed changes

class Login extends Controller
{
    public function store(Request $request)
    {
        $credentials = $request->validate([
<<<<<<< Updated upstream
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/')->with('success', 'Welcome back!');
        }

        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ]);
    }
}
=======
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
}
>>>>>>> Stashed changes
