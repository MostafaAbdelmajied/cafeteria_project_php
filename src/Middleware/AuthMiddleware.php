<?php

namespace Src\Middleware;

class AuthMiddleware
{
    public function handle()
    {
        if (!isset($_SESSION['user_id'])) {
            redirect(url("/login"));
        }
    }
}