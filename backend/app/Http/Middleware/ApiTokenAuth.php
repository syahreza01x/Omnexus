<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (! $token) {
            return new JsonResponse([
                'message' => 'Unauthorized.',
            ], 401);
        }

        $userId = Cache::get($this->cacheKey($token));

        if (! $userId) {
            return new JsonResponse([
                'message' => 'Token tidak valid atau sudah kedaluwarsa.',
            ], 401);
        }

        $user = User::find($userId);

        if (! $user) {
            return new JsonResponse([
                'message' => 'User tidak ditemukan.',
            ], 401);
        }

        $request->setUserResolver(fn () => $user);

        return $next($request);
    }

    private function cacheKey(string $token): string
    {
        return 'api_token:'.$token;
    }
}
