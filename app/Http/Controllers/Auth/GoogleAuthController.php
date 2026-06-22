<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\GoogleCallbackRequest;
use App\Services\Auth\GoogleAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        if (
            blank(config('services.google.client_id'))
            || blank(config('services.google.client_secret'))
            || blank(config('services.google.redirect'))
        ) {
            return redirect()
                ->route('login')
                ->with('status', 'Konfigurasi Google OAuth belum lengkap. Isi GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, dan GOOGLE_REDIRECT_URI di file .env.');
        }

        /** @var \Laravel\Socialite\Two\GoogleProvider $driver */
        $driver = Socialite::driver('google');

        return $driver->stateless()->redirect();
    }

    public function callback(GoogleCallbackRequest $request, GoogleAuthService $googleAuthService): RedirectResponse
    {
        if ($request->validated('error')) {
            return redirect()
                ->route('login')
                ->with('status', $request->validated('error_description') ?: 'Google login dibatalkan.');
        }

        try {
            /** @var \Laravel\Socialite\Two\GoogleProvider $driver */
            $driver = Socialite::driver('google');
            $googleUser = $driver->stateless()->user();
            $user = $googleAuthService->findOrCreateUser($googleUser);
        } catch (\Exception $e) {
            return redirect()
                ->route('login')
                ->with('status', 'Gagal masuk dengan Google. Silakan coba lagi (hindari me-refresh halaman login).');
        }

        Auth::login($user, remember: true);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
