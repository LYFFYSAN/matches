<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminLoginController extends Controller
{
    public function showLogin()
    {
        if (session('admin_authenticated')) {
            return redirect()->route('admin.index');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate(['secret' => 'required|string']);

        if ($request->secret === config('app.admin_secret', env('ADMIN_SECRET', 'changeme'))) {
            session(['admin_authenticated' => true]);
            return redirect()->route('admin.index');
        }

        return back()->withErrors(['secret' => 'Invalid admin secret.']);
    }

    public function logout()
    {
        session()->forget('admin_authenticated');
        return redirect()->route('admin.login');
    }
}
