<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AdminLoginRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->validated();

        $response = Http::post('http://127.0.0.1:8001/api/login', [
            'username' => $request->username,
            'password' => $request->password,
        ]);



        if ($response->successful()) {
            $data = $response->json();

            $user = User::where('kode', $data['user']['username'])->first();

            if (!$user) {
                return back()->withErrors([
                    'username' => 'User tidak ditemukan di sistem',
                ]);
            }

            Auth::login($user);
            $request->session()->regenerate();
            session(['token' => $data['token']]);

            return redirect()->route('dashboard');
        }

        if ($response->failed()) {
            return back()->withErrors([
                'username' => 'Gagal terhubung ke server autentikasi',
            ]);
        }

        return back()->withErrors([
            'username' => 'Username atau password salah',
        ])->onlyInput('username');
    }

    public function loginAdmin(AdminLoginRequest $request): RedirectResponse
    {
      $request->authenticate();
      $request->session()->regenerate();

      return redirect()->intended(route('dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
