<?php
if (defined('BOOTSTRAP_LOADED')) return;
define('BOOTSTRAP_LOADED', true);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!function_exists('env')) {
    function env($key, $default = null)
    {
        return isset($_ENV[$key]) ? $_ENV[$key] : $default;
    }
}

// store a one-time message in the session
if (!function_exists('setFlash')) {
    function setFlash($type, $message)
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }
}

// retrieve and delete the flash message in one go
if (!function_exists('getFlash')) {
    function getFlash()
    {
        $flash = isset($_SESSION['flash']) ? $_SESSION['flash'] : null;
        unset($_SESSION['flash']);
        return $flash;
    }
}

// redirect to a path, BASE_PATH is prepended automatically
if (!function_exists('redirect')) {
    function redirect($path)
    {
        header('Location: ' . BASE_PATH . $path);
        exit;
    }
}

// load a view file and extract data into its scope
if (!function_exists('render')) {
    function render($view, $data = [])
    {
        extract($data);
        $path = __DIR__ . '/views/' . $view . '.php';
        if (!file_exists($path)) {
            http_response_code(404);
            require __DIR__ . '/views/404.php';
            exit;
        }
        require $path;
    }
}
// ── BASE_PATH ─────────────────────────────────────────────────────────────────
if (!defined('BASE_PATH')) {
    $docRoot    = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '/');
    $projectDir = str_replace('\\', '/', __DIR__);
    $base       = str_replace($docRoot, '', $projectDir);
    $base       = rtrim($base, '/');
    define('BASE_PATH', $base);
}
// ─────────────────────────────────────────────────────────────────────────────
// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'Src\\';
    $base   = __DIR__ . '/src/';
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) return;
    $relative = substr($class, strlen($prefix));
    $file     = $base . str_replace('\\', '/', $relative) . '.php';
    if (file_exists($file)) require $file;
});
