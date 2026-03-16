<?php

namespace Src\Classes;

class Validators
{
    static function required($value): bool
    {
        if (is_array($value)) {
            return empty($value);
        }
        return trim((string)$value) === '';
    }

    static function min($value, $min): bool
    {
        if (is_string($value)) {
            return strlen($value) < $min;
        }
        return $value < $min;
    }

    static function max($value, $max): bool
    {
        if (is_string($value)) {
            return strlen($value) > $max;
        }
        return $value > $max;
    }

    static function stringValidator($str, $min, $max): bool
    {
        return (static::min($str, $min) || static::max($str, $max));
    }

    static function emailValidator($email): bool
    {
        return !filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    static function numberValidator($number): bool
    {
        return !is_numeric($number);
    }

    static function greaterThan($value, $min): bool
    {
        return !is_numeric($value) || (float)$value <= $min;
    }

    static function passwordMatchConfirmPassword($password, $confirmPassword): bool
    {
        return ($password !== $confirmPassword);
    }

    static function passwordValidator($password): bool
    {
        $pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";
        return (!preg_match($pattern, $password));
    }

    static function checkFileSize(array $file, $maxSize): bool
    {
        return ($file["size"] > $maxSize);
    }

    static function isFileUploaded(array $file): bool
    {
        return (!isset($file["error"]) || $file["error"] !== UPLOAD_ERR_OK);
    }

    static function validateFileType(array $file): bool
    {
        $allowedTypes = ["image/jpeg", "image/jpg", "image/png", "image/gif"];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        return !in_array($mimeType, $allowedTypes);
    }
}