<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(Response::HTTP_UNAUTHORIZED);
        }

        $allowedRoles = collect($roles)
            ->map(fn (string $role): string => UserRole::tryFrom($role)?->value ?? $role)
            ->all();

        // In Laravel, the $user->role is cast to an App\Enums\UserRole instance if configured in User model.
        // We ensure we get the string value for comparison.
        $userRoleValue = $user->role instanceof UserRole ? $user->role->value : $user->role;

        if (!in_array($userRoleValue, $allowedRoles, true)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
