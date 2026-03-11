<?php

namespace Src\Controllers;

use Src\Classes\Validators;
use Src\Models\PasswordReset;
use Src\Models\User;

class PasswordResetController
{
    private const EXPIRY_MINUTES = 5;

    public function showForgotPassword()
    {
        return view('forgot-password.php');
    }

    public function sendResetLink()
    {
        $email = trim($_POST['email'] ?? '');
        $errors = [];

        if ($email === '') {
            $errors[] = 'Email is required.';
        } elseif (Validators::emailValidator($email)) {
            $errors[] = 'Please enter a valid email address.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old']['email'] = $email;
            redirect(url('/forgot-password'));
        }

        $user = User::query()->where('email', $email)->get()[0] ?? null;

        if ($user) {
            PasswordReset::deleteByEmail($email);

            $token = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', time() + self::EXPIRY_MINUTES * 60);

            PasswordReset::create([
                'email' => $email,
                'token' => $token,
                'expires_at' => $expiresAt,
            ]);

            $_SESSION['reset_link'] = url('/reset-password?token=' . $token);
        }

        $_SESSION['status'] = 'If the email exists, a password reset link has been generated.';
        redirect(url('/forgot-password'));
    }

    public function showResetPassword()
    {
        $token = trim($_GET['token'] ?? '');
        $reset = PasswordReset::findValidToken($token);

        if (!$reset) {
            $_SESSION['errors'] = ['This password reset link is invalid or expired.'];
            redirect(url('/forgot-password'));
        }

        return view('reset-password.php', ['token' => $token]);
    }

    public function resetPassword()
    {
        $token = trim($_POST['token'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['password_confirmation'] ?? '';
        $errors = [];

        $reset = PasswordReset::findValidToken($token);
        if (!$reset) {
            $_SESSION['errors'] = ['This password reset link is invalid or expired.'];
            redirect(url('/forgot-password'));
        }

        if ($password === '') {
            $errors[] = 'Password is required.';
        } elseif (Validators::stringValidator($password, 6, 255)) {
            $errors[] = 'Password must be at least 6 characters.';
        }

        if ($confirmPassword === '') {
            $errors[] = 'Password confirmation is required.';
        } elseif (Validators::passwordMatchConfirmPassword($password, $confirmPassword)) {
            $errors[] = 'Passwords do not match.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            redirect(url('/reset-password?token=' . urlencode($token)));
        }

        $user = User::query()->where('email', $reset['email'])->get()[0] ?? null;
        if (!$user) {
            PasswordReset::deleteByEmail($reset['email']);
            $_SESSION['errors'] = ['No user account was found for this reset request.'];
            redirect(url('/forgot-password'));
        }

        User::update($user['id'], [
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        PasswordReset::deleteByEmail($reset['email']);
        unset($_SESSION['reset_link']);

        $_SESSION['status'] = 'Your password has been reset. You can now log in.';
        redirect(url('/login'));
    }
}
