<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'email' => 'Las credenciales no coinciden con nuestros registros.',
                ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        $roleId = $user ? (int) ($user->rol_id ?? 0) : 0;

        if ($roleId === 1) {
            return redirect('/admin/dashboard');
        }

        if ($roleId === 2) {
            return redirect('/dashboard');
        }

        if ($roleId === 3) {
            return redirect('/entrenador/dashboard');
        }

        if ($user && $user->rol === 'admin') {
            return redirect('/admin/dashboard');
        }

        if ($user && $user->rol === 'dueno') {
            return redirect('/dashboard');
        }

        if ($user && $user->rol === 'entrenador') {
            return redirect('/entrenador/dashboard');
        }

        if ($user && ($user->rol === 'cuidador' || $user->rol === 'empleado')) {
            return redirect('/cuidador/dashboard');
        }

        return redirect('/home');
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
