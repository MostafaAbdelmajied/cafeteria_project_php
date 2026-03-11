<?php

namespace Src\Middleware;

use Src\Classes\Auth;

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
            redirect(url('/'));
        }

        if ($type === 'user' && $isAdmin) {
            redirect(url('/admin'));
        }
    }
}
