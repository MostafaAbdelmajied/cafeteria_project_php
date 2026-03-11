<?php

namespace Src\Models;

use PDO;
use Src\Classes\DB;

class PasswordReset extends Model
{
    protected static $table = 'password_resets';

    public static function deleteByEmail($email)
    {
        $stmt = DB::conn()->prepare('DELETE FROM password_resets WHERE email = ?');
        $stmt->execute([$email]);

        return true;
    }

    public static function findValidToken($token)
    {
        if ($token === '') {
            return null;
        }

        $stmt = DB::conn()->prepare(
            'SELECT * FROM password_resets WHERE token = ? AND expires_at >= NOW() LIMIT 1'
        );
        $stmt->execute([$token]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
