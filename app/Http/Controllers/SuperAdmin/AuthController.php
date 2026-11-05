<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Credenciales hardcoded del superadmin dueño de la plataforma.
     * Cambia el email y password cuando lo desees.
     */
    private const ADMIN_EMAIL    = 'admin@academiahe5.com';
    private const ADMIN_PASSWORD = '30042013Yoz';

    public function showLogin()
    {
        if (session('superadmin_logged')) {
            return redirect()->route('superadmin.dashboard');
        }
        return view('superadmin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $emailOk    = $request->email    === self::ADMIN_EMAIL;
        $passwordOk = $request->password === self::ADMIN_PASSWORD;

        if ($emailOk && $passwordOk) {
            session(['superadmin_logged' => true, 'superadmin_email' => $request->email]);
            return redirect()->route('superadmin.dashboard');
        }

        return redirect()->back()
            ->withInput(['email' => $request->email])
            ->with('error', 'Credenciales incorrectas. Acceso denegado.');
    }

    public function logout()
    {
        session()->forget(['superadmin_logged', 'superadmin_email']);
        return redirect()->route('superadmin.login');
    }
}
