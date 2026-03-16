<?php

namespace Src\Classes;

use Src\Models\User;

class Auth
{
    private static $user = false;

    public static function check(): bool
    {
        return static::user() !== null;
    }

    public static function user()
    {
        if (static::$user !== false) {
            return static::$user;
        }

        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            static::$user = null;
            return null;
        }

        $user = User::find($userId);
        if (!$user) {
            unset($_SESSION['user_id']);
            static::$user = null;
            return null;
        }

        static::$user = $user;
        return static::$user;
    }

    public static function login(array $user): void
    {
        $_SESSION['user_id'] = $user['id'];
        static::$user = $user;
    }

    public static function logout(): void
    {
        unset($_SESSION['user_id']);
        static::$user = null;
        session_destroy();
    }
}
