<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'login' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        $loginValue = trim($validated['login']);
        $field = filter_var($loginValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        $user = User::query()->where($field, $loginValue)->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['Username/email atau password salah.'],
            ]);
        }

        $token = Str::random(64);
        Cache::put($this->cacheKey($token), $user->id, now()->addDays(7));

        return response()->json([
            'message' => 'Login berhasil.',
            'token' => $token,
            'user' => $this->formatUser($user),
        ]);
    }

    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:users,name'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        $token = Str::random(64);
        Cache::put($this->cacheKey($token), $user->id, now()->addDays(7));

        return response()->json([
            'message' => 'Registrasi berhasil.',
            'token' => $token,
            'user' => $this->formatUser($user),
        ], 201);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $this->formatUser($request->user()),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $token = $request->bearerToken();

        if ($token) {
            Cache::forget($this->cacheKey($token));
        }

        return response()->json([
            'message' => 'Logout berhasil.',
        ]);
    }

    private function formatUser(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'profile_photo_url' => $user->profile_photo_path ? asset('storage/'.$user->profile_photo_path) : null,
        ];
    }

    private function cacheKey(string $token): string
    {
        return 'api_token:'.$token;
    }
}
