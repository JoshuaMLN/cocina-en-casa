<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    // Muestra la pantalla de login
    public function showLogin()
    {
        // Si ya está logueado, lo mandamos directo al panel
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    // Procesa el formulario
    public function login(Request $request)
    {
        // Validamos campos exigentes
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Intentamos el login usando el guard 'admin'
        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate(); // Seguridad contra ataques de fijación de sesión

            return redirect()->route('admin.dashboard')
                ->with('success', '¡Bienvenido de nuevo al Panel!');
        }

        // Si falla, regresamos con el error que tu app.js capturará en un Toast automático
        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'Sesión cerrada correctamente.');
    }
}
