<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $dados = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        $user = User::create([
            'name' => $dados['name'],
            'email' => $dados['email'],
            'password' => Hash::make($dados['password']),
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = auth()->user();

            if ($user->isBlocked()) {
                Auth::logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $message = 'Sua conta está bloqueada.';

                if ($user->account_status === 'inactive') {
                    $message = 'Sua conta está desativada.';
                }

                if ($user->account_status === 'suspended') {
                    $message = 'Sua conta está suspensa.';

                    if ($user->suspended_until) {
                        $message .= ' Até: ' . $user->suspended_until->format('d/m/Y H:i');
                    }

                    if ($user->suspension_reason) {
                        $message .= ' Motivo: ' . $user->suspension_reason;
                    }
                }

                return redirect()
                    ->route('login')
                    ->withErrors([
                        'email' => $message,
                    ]);
            }

            return redirect()->intended(route('dashboard'));
        }

        return back()
            ->withErrors([
                'email' => 'E-mail ou senha inválidos.',
            ])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}