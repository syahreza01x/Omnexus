<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect users to Google OAuth screen.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        $clientId = (string) config('services.google.client_id');
        $clientSecret = (string) config('services.google.client_secret');
        $redirectUri = (string) config('services.google.redirect', url('/auth/google/callback'));

        if ($clientId === '' || $clientSecret === '' || $redirectUri === '') {
            return redirect()->route('login')->withErrors([
                'login' => 'Konfigurasi Google login belum lengkap. Isi GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, dan GOOGLE_REDIRECT_URI di file .env.',
            ]);
        }

        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback from Google and log in or register user.
     */
    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'login' => 'Gagal menghubungi Google. Silakan coba lagi.',
            ]);
        }

        $email = $googleUser->getEmail();

        if (! $email) {
            return redirect()->route('login')->withErrors([
                'login' => 'Akun Google Anda tidak memiliki email yang bisa digunakan untuk masuk.',
            ]);
        }

        $user = User::query()
            ->where('google_id', $googleUser->getId())
            ->orWhere('email', $email)
            ->first();

        if (! $user) {
            $baseName = Str::slug($googleUser->getName() ?: $googleUser->getNickname() ?: 'user', '_');
            $baseName = $baseName !== '' ? $baseName : 'user';
            $name = $this->uniqueName($baseName);

            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make(Str::random(32)),
                'google_id' => $googleUser->getId(),
            ]);

            event(new Registered($user));
        } elseif (! $user->google_id) {
            $user->update([
                'google_id' => $googleUser->getId(),
            ]);
        }

        Auth::login($user, true);

        if ($user->role === 'super_admin') {
            return redirect()->intended(route('admin.super.dashboard'));
        } elseif ($user->role === 'admin_web') {
            return redirect()->intended(route('admin.web.dashboard'));
        } elseif ($user->role === 'admin_warehouse') {
            return redirect()->intended(route('admin.warehouse.dashboard'));
        }

        return redirect()->intended(route('beranda'));
    }

    private function uniqueName(string $baseName): string
    {
        $candidate = $baseName;
        $index = 1;

        while (User::query()->where('name', $candidate)->exists()) {
            $candidate = $baseName.'_'.$index;
            $index++;
        }

        return $candidate;
    }
}
