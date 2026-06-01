<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required_without:name', 'nullable', 'string', 'max:50', 'alpha_dash', 'unique:'.User::class.',name'],
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $username = (string) $request->input('username', '');

        if ($username === '') {
            $username = Str::slug((string) $request->input('name', 'user'), '_');
            $username = $username !== '' ? $username : 'user';

            $base = $username;
            $index = 1;
            while (User::query()->where('name', $username)->exists()) {
                $username = $base.'_'.$index;
                $index++;
            }
        }

        $user = User::create([
            'name' => $username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('beranda', absolute: false));
    }
}
