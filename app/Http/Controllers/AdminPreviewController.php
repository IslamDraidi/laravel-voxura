<?php

namespace App\Http\Controllers;

class AdminPreviewController extends Controller
{
    /** GET /admin/preview/enable — set preview session, redirect to storefront */
    public function enable()
    {
        session(['admin_preview_mode' => true]);

        return redirect('/');
    }

    /** GET /admin/preview/disable — clear preview session, redirect to admin */
    public function disable()
    {
        session()->forget('admin_preview_mode');

        return redirect('/admin');
    }
}
