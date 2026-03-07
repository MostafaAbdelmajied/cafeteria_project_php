<?php

$lines = file(__DIR__ . '/.env');

foreach ($lines as $line) {
    $line = trim($line);

    if ($line === '' || str_starts_with($line, '#')) {
        continue;
    }

    [$key, $value] = explode('=', $line, 2);

    $_ENV[trim($key)] = trim($value);
}

function env($key, $default = null)
{
    return $_ENV[$key] ?? $default;
}