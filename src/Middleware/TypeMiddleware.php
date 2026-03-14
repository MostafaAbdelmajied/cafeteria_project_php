<?php

namespace Src\Middleware;

use Src\Classes\Auth;
use Src\Exceptions\PermissionDeniedException;

class TypeMiddleware
{
    public function handle($type)
    {
        if (!Auth::check()) {
            redirect(url('/login'));
        }

        $user = Auth::user();
        $isAdmin = (bool) ($user['is_admin'] ?? false);

        if ($type === 'admin' && !$isAdmin) {
            throw new PermissionDeniedException();
        }

        if ($type === 'user' && $isAdmin) {
            throw new PermissionDeniedException();
        }
    }
}
