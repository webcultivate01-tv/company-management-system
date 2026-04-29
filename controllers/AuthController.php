<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/User.php';

class AuthController extends BaseController {
    private User $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login(): void {
        if (!empty($_SESSION['user_id'])) {
            $this->redirectByRole($_SESSION['role']);
        }

        if ($this->isPost()) {
            $email    = strtolower(trim($this->post('email')));
            $password = $this->post('password');

            if (empty($email) || empty($password)) {
                $this->view('auth/login', ['error' => 'Email and password are required']);
                return;
            }

            $user = $this->userModel->findByEmail($email);

            if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
                $this->view('auth/login', ['error' => 'Invalid email or password']);
                return;
            }

            if (!($user['isActive'] ?? true)) {
                $this->view('auth/login', ['error' => 'Your account has been deactivated. Contact admin.']);
                return;
            }

            // Regenerate session ID to prevent fixation
            session_regenerate_id(true);

            $_SESSION['user_id'] = (string)$user['_id'];
            $_SESSION['role']    = $user['role'];
            $_SESSION['name']    = $user['name'];
            $_SESSION['email']   = $user['email'];
            $_SESSION['image']   = $user['profileImage'] ?? '';
            $_SESSION['user']    = [
                '_id'          => (string)$user['_id'],
                'name'         => $user['name'],
                'email'        => $user['email'],
                'role'         => $user['role'],
                'profileImage' => $user['profileImage'] ?? '',
            ];

            $this->redirectByRole($user['role']);
        }

        $this->view('auth/login', ['error' => null]);
    }

    public function logout(): void {
        session_destroy();
        $this->redirect('login');
    }

    private function redirectByRole(string $role): void {
        match ($role) {
            'admin'  => $this->redirect('admin/dashboard'),
            default  => $this->redirect('employee/dashboard'),
        };
    }
}
