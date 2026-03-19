<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'message' => 'required|string|min:5|max:2000',
        ]);

        // Message is saved to session for now
        // You can add Mail::to(...) here later when email is configured

        return redirect('/#contact')->with('success', 'Message sent! We\'ll get back to you soon.');
    }
}