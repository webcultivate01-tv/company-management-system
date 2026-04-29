<?php
// ─── Bootstrap ───────────────────────────────────────────────────────────────
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/vendor/autoload.php';

// Session setup
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_samesite', 'Strict');
session_name(SESSION_NAME);
session_set_cookie_params(['lifetime' => SESSION_LIFETIME, 'path' => '/']);
session_start();

// ─── Router ──────────────────────────────────────────────────────────────────
$routes = require_once __DIR__ . '/routes/web.php';

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri    = trim($_GET['url'] ?? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

if (empty($requestUri)) {
    $requestUri = empty($_SESSION['user_id']) ? 'login' : (
        $_SESSION['role'] === 'admin' ? 'admin/dashboard' : 'employee/dashboard'
    );
}

$routeKey = $requestMethod . ':' . $requestUri;

// Check exact match first
if (isset($routes[$routeKey])) {
    [$controllerName, $method] = $routes[$routeKey];
    $params = [];
} else {
    // Try dynamic routes with {id} placeholders
    $matched = false;
    foreach ($routes as $pattern => $handler) {
        if (!str_contains($pattern, '{')) continue;

        $patternMethod = explode(':', $pattern, 2)[0];
        $patternPath   = explode(':', $pattern, 2)[1];

        if ($patternMethod !== $requestMethod) continue;

        $regex = '#^' . preg_replace('/\{[^}]+\}/', '([^/]+)', $patternPath) . '$#';
        if (preg_match($regex, $requestUri, $matches)) {
            [$controllerName, $method] = $handler;
            array_shift($matches);
            $params  = $matches;
            $matched = true;
            break;
        }
    }

    if (!$matched) {
        http_response_code(404);
        require_once __DIR__ . '/views/errors/404.php';
        exit;
    }
}

// Load and dispatch controller
$controllerFile = __DIR__ . '/controllers/' . $controllerName . '.php';
if (!file_exists($controllerFile)) {
    http_response_code(500);
    die('Controller not found: ' . htmlspecialchars($controllerName));
}

require_once $controllerFile;
$controller = new $controllerName();
call_user_func_array([$controller, $method], $params ?? []);
