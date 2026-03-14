<?php

namespace Src\Controllers;

use Src\Classes\Auth;
use Src\Models\User;

class AuthController{
    public function showLogin()
    {
        return view('login.php');
    }

    public function login()
    {
        $errors = [];
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $errors[] = 'Email and password are required.';
            $_SESSION['errors'] = $errors;
            redirect(url("/login"));
        }

        $user = User::query()->where('email', $email)->get()[0] ?? null;
        if ($user && password_verify($password, $user['password'])) {
            Auth::login($user);

            $isAdmin = (bool) ($user['is_admin'] ?? false);
            $redirectPath = $isAdmin ? '/admin' : '/';
            redirect(url($redirectPath));
        } else {
            $errors[] = 'Invalid email or password.';
            $_SESSION['errors'] = $errors;
            redirect(url("/login"));
        }

    }

    public function logout()
    {
        Auth::logout();
        redirect(url('/login'));
    }
}
