<?php

namespace Src\Middleware;

use Src\Classes\Auth;

class Guest
{
    public function handle()
    {
        if (Auth::check()) {
            $isAdmin = (bool) (Auth::user()['is_admin'] ?? false);
            $redirectPath = $isAdmin ? '/admin' : '/';
            redirect(url($redirectPath));
        }
    }
}
