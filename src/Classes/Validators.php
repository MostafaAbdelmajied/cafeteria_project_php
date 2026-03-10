<?php

namespace Src\Classes;

class Validators
{
    static function stringValidator($str, $min, $max): bool
    {
        return (strlen($str) < $min || strlen($str) > $max);
    }

    static function emailValidator($email): bool
    {
        return !filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    static function numberValidator($number): bool
    {
        return is_numeric($number);
    }

    static function passwordMatchConfirmPassword($password, $confirmPassword): bool
    {
        return ($password !== $confirmPassword);
    }

    static function passwordValidator($password): bool
    {
        $pattern = "^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$";
        return (!preg_match($pattern, $password));
    }

    static function checkFileSize(array $file, $maxSize): bool
    {
        return ($file["size"] > $maxSize);
    }

    static function isFileUploaded(array $file): bool
    {
        return (isset($file["error"]) && $file["error"] === UPLOAD_ERR_OK);
    }

    static function validateFileType(array $file): bool
    {
        $allowedTypes = ["images/jpeg", 'images/png'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        return in_array($mimeType, $allowedTypes);
    }
}