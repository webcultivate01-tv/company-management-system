<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Attendance.php';
require_once __DIR__ . '/../models/Lead.php';
require_once __DIR__ . '/../config/cloudinary.php';

class UserController extends BaseController {
    private User       $userModel;
    private Attendance $attendanceModel;
    private Lead       $leadModel;

    public function __construct() {
        $this->userModel       = new User();
        $this->attendanceModel = new Attendance();
        $this->leadModel       = new Lead();
    }

    public function index(): void {
        $this->requireRole('admin');
        $users = $this->userModel->getAllUsers(false);
        $this->view('admin/users', ['users' => $users, 'flash' => $this->getFlash()]);
    }

    public function create(): void {
        $this->requireRole('admin');

        if ($this->isPost()) {
            $data = [
                'name'  => $this->sanitize($this->post('name')),
                'email' => $this->sanitize($this->post('email')),
                'password' => $this->post('password'),
                'role'  => $this->sanitize($this->post('role')),
            ];

            if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
                $this->view('admin/user-form', ['error' => 'All fields are required', 'user' => $data, 'action' => 'create']);
                return;
            }

            // Handle profile image upload
            if (!empty($_FILES['profileImage']['tmp_name'])) {
                $url = CloudinaryConfig::upload($_FILES['profileImage']['tmp_name']);
                $data['profileImage'] = $url ?? '';
            }

            $id = $this->userModel->createUser($data);
            if (!$id) {
                $this->view('admin/user-form', ['error' => 'Email already exists', 'user' => $data, 'action' => 'create']);
                return;
            }

            $this->setFlash('success', 'User created successfully');
            $this->redirect('admin/users');
        }

        $this->view('admin/user-form', ['user' => null, 'action' => 'create', 'error' => null]);
    }

    public function edit(string $id): void {
        $this->requireRole('admin');
        $user = $this->userModel->findById($id);
        if (!$user) { $this->redirect('admin/users'); }

        if ($this->isPost()) {
            $data = [
                'name'  => $this->sanitize($this->post('name')),
                'email' => $this->sanitize($this->post('email')),
                'role'  => $this->sanitize($this->post('role')),
            ];

            if (!empty($_FILES['profileImage']['tmp_name'])) {
                $url = CloudinaryConfig::upload($_FILES['profileImage']['tmp_name']);
                if ($url) $data['profileImage'] = $url;
            }

            $this->userModel->updateById($id, $data);
            $this->setFlash('success', 'User updated successfully');
            $this->redirect('admin/users');
        }

        $this->view('admin/user-form', ['user' => $user, 'action' => 'edit', 'error' => null]);
    }

    public function delete(string $id): void {
        $this->requireRole('admin');
        $this->userModel->deleteById($id);
        $this->attendanceModel->deleteMany(['userId' => $id]);
        $this->setFlash('success', 'User and all related data deleted successfully');
        $this->redirect('admin/users');
    }

    public function toggleActive(string $id): void {
        $this->requireRole('admin');
        $user = $this->userModel->findById($id);
        if ($user) {
            $this->userModel->toggleActive($id, !($user['isActive'] ?? true));
            $this->setFlash('success', 'User status updated');
        }
        $this->redirect('admin/users');
    }

    public function resetPassword(string $id): void {
        $this->requireRole('admin');
        if ($this->isPost()) {
            $newPassword = $this->post('password');
            if (strlen($newPassword) < 6) {
                $this->setFlash('error', 'Password must be at least 6 characters');
            } else {
                $this->userModel->updatePassword($id, $newPassword);
                $this->setFlash('success', 'Password reset successfully');
            }
        }
        $this->redirect('admin/users');
    }

    public function profile(): void {
        $this->requireAuth();
        $user = $this->userModel->findById($_SESSION['user_id']);
        $rolePrefix = $_SESSION['role'] === 'admin' ? 'admin' : 'employee';

        if ($this->isPost()) {
            // Handle password change
            if ($this->post('change_password')) {
                $current = $this->post('current_password');
                $new     = $this->post('new_password');
                $confirm = $this->post('confirm_password');

                if (!$this->userModel->verifyPassword($current, $user['password'])) {
                    $this->view($rolePrefix . '/profile', ['user' => $user, 'flash' => ['type' => 'error', 'message' => 'Current password is incorrect']]);
                    return;
                }
                if (strlen($new) < 6) {
                    $this->view($rolePrefix . '/profile', ['user' => $user, 'flash' => ['type' => 'error', 'message' => 'New password must be at least 6 characters']]);
                    return;
                }
                if ($new !== $confirm) {
                    $this->view($rolePrefix . '/profile', ['user' => $user, 'flash' => ['type' => 'error', 'message' => 'Passwords do not match']]);
                    return;
                }
                $this->userModel->updatePassword($_SESSION['user_id'], $new);
                $this->setFlash('success', 'Password changed successfully');
                $this->redirect($rolePrefix . '/profile');
                return;
            }

            // Handle profile update
            $data = ['name' => $this->sanitize($this->post('name'))];
            if (!empty($_FILES['profileImage']['tmp_name'])) {
                $url = CloudinaryConfig::upload($_FILES['profileImage']['tmp_name']);
                if ($url) {
                    $data['profileImage'] = $url;
                    $_SESSION['image']    = $url;
                }
            }
            $this->userModel->updateById($_SESSION['user_id'], $data);
            $_SESSION['name'] = $data['name'];
            $this->setFlash('success', 'Profile updated successfully');
            $this->redirect($rolePrefix . '/profile');
        }

        $this->view($rolePrefix . '/profile', ['user' => $user, 'flash' => $this->getFlash()]);
    }
}
