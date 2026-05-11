<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'message' => 'required|string|max:2000',
        ]);

        Log::info('Contact form submission', [
            'name'    => $validated['name'],
            'email'   => $validated['email'],
            'message' => $validated['message'],
        ]);

        try {
            Mail::send(
                'emails.contact',
                [
                    'name'        => $validated['name'],
                    'email'       => $validated['email'],
                    'userMessage' => $validated['message'],
                ],
                function ($mail) use ($validated) {
                    $mail->to(config('mail.from.address'), 'Voxura Team')
                         ->subject('Contact: ' . $validated['name'])
                         ->replyTo($validated['email'], $validated['name']);
                }
            );
        } catch (\Exception $e) {
            Log::error('Contact mail failed: ' . $e->getMessage());
        }

        return back()->with('contact_success', __('general.contact_success'));
    }
}
