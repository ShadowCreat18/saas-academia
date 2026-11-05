<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        try {
            $demoTenant = \App\Models\Central\Tenant::where('subdomain', 'demo')->first();
            if (!\App\Models\User::where('email', 'demo@academiahe5.com')->exists()) {
                \App\Models\User::create([
                    'name'      => 'Director Demo',
                    'email'     => 'demo@academiahe5.com',
                    'password'  => \Illuminate\Support\Facades\Hash::make('password'),
                    'role'      => 'admin',
                    'tenant_id' => $demoTenant ? $demoTenant->id : null,
                ]);
            }
            if (!\App\Models\User::where('email', 'carlos.tutor@ejemplo.com')->exists()) {
                \App\Models\User::create([
                    'name'      => 'Carlos Martínez',
                    'email'     => 'carlos.tutor@ejemplo.com',
                    'password'  => \Illuminate\Support\Facades\Hash::make('123456'),
                    'role'      => 'parent',
                    'tenant_id' => $demoTenant ? $demoTenant->id : null,
                ]);
            }
        } catch (\Exception $e) {}

        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->isSuperAdmin()) {
                return redirect()->intended(route('superadmin.dashboard'));
            }

            if (Auth::user()->role === 'parent' || Auth::user()->role === 'tutor') {
                return redirect()->intended(route('tenant.frontend'));
            }

            return redirect()->intended(route('tenant.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
