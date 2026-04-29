<?php
class BaseController {
    protected function view(string $viewPath, array $data = []): void {
        extract($data);
        $fullPath = __DIR__ . '/../views/' . $viewPath . '.php';
        if (!file_exists($fullPath)) {
            http_response_code(404);
            die('View not found: ' . htmlspecialchars($viewPath));
        }
        require_once $fullPath;
    }

    protected function redirect(string $path): void {
        header('Location: ' . BASE_URL . '/' . ltrim($path, '/'));
        exit;
    }

    protected function json(array $data, int $code = 200): void {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function requireAuth(): void {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }
    }

    protected function requireRole(string ...$roles): void {
        $this->requireAuth();
        if (!in_array($_SESSION['role'] ?? '', $roles)) {
            http_response_code(403);
            $this->view('errors/403');
            exit;
        }
    }

    protected function sanitize(string $value): string {
        return htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
    }

    protected function isPost(): bool {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function post(string $key, mixed $default = ''): mixed {
        return $_POST[$key] ?? $default;
    }

    protected function get(string $key, mixed $default = ''): mixed {
        return $_GET[$key] ?? $default;
    }

    protected function setFlash(string $type, string $message): void {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    protected function getFlash(): ?array {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $flash;
    }

    protected function currentUser(): ?array {
        return $_SESSION['user'] ?? null;
    }
}
