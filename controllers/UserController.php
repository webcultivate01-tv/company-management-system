<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Attendance.php';
require_once __DIR__ . '/../models/Lead.php';
require_once __DIR__ . '/../models/Position.php';
require_once __DIR__ . '/../config/cloudinary.php';

class UserController extends BaseController {
    private User       $userModel;
    private Attendance $attendanceModel;
    private Lead       $leadModel;
    private Position   $positionModel;

    public function __construct() {
        $this->userModel       = new User();
        $this->attendanceModel = new Attendance();
        $this->leadModel       = new Lead();
        $this->positionModel   = new Position();
    }

    public function index(): void {
        $this->requireRole('admin');
        $users = $this->userModel->getAllUsers(false);
        $this->view('admin/users', ['users' => $users, 'flash' => $this->getFlash()]);
    }

    public function create(): void {
        $this->requireRole('admin');
        $positions = $this->positionModel->getAllPositions();

        if ($this->isPost()) {
            $data = [
                'name'             => $this->sanitize($this->post('name')),
                'email'            => $this->sanitize($this->post('email')),
                'password'         => $this->post('password'),
                'role'             => $this->sanitize($this->post('role')),
                'position'         => $this->sanitize($this->post('position')),
                'department'       => $this->sanitize($this->post('department')),
                'phone'            => $this->sanitize($this->post('phone')),
                'joiningDate'      => $this->sanitize($this->post('joiningDate')),
                'salary'           => (float)$this->post('salary'),
                'address'          => $this->sanitize($this->post('address')),
                'emergencyContact' => $this->sanitize($this->post('emergencyContact')),
                'emergencyPhone'   => $this->sanitize($this->post('emergencyPhone')),
                'bloodGroup'       => $this->sanitize($this->post('bloodGroup')),
                'skills'           => array_filter(array_map('trim', explode(',', $this->post('skills')))),
                'bio'              => $this->sanitize($this->post('bio')),
                'nationalId'       => $this->sanitize($this->post('nationalId')),
                'bankAccount'      => $this->sanitize($this->post('bankAccount')),
            ];

            if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
                $this->view('admin/user-form', ['error' => 'Name, email and password are required', 'user' => $data, 'action' => 'create', 'positions' => $positions]);
                return;
            }

            if (!empty($_FILES['profileImage']['tmp_name'])) {
                $url = CloudinaryConfig::upload($_FILES['profileImage']['tmp_name']);
                $data['profileImage'] = $url ?? '';
            }

            $id = $this->userModel->createUser($data);
            if (!$id) {
                $this->view('admin/user-form', ['error' => 'Email already exists', 'user' => $data, 'action' => 'create', 'positions' => $positions]);
                return;
            }

            $this->setFlash('success', 'Employee registered successfully');
            $this->redirect('admin/users');
        }

        $this->view('admin/user-form', ['user' => null, 'action' => 'create', 'error' => null, 'positions' => $positions]);
    }

    public function edit(string $id): void {
        $this->requireRole('admin');
        $user = $this->userModel->findById($id);
        if (!$user) { $this->redirect('admin/users'); }
        $positions = $this->positionModel->getAllPositions();

        if ($this->isPost()) {
            $data = [
                'name'             => $this->sanitize($this->post('name')),
                'email'            => $this->sanitize($this->post('email')),
                'role'             => $this->sanitize($this->post('role')),
                'position'         => $this->sanitize($this->post('position')),
                'department'       => $this->sanitize($this->post('department')),
                'phone'            => $this->sanitize($this->post('phone')),
                'joiningDate'      => $this->sanitize($this->post('joiningDate')),
                'salary'           => (float)$this->post('salary'),
                'address'          => $this->sanitize($this->post('address')),
                'emergencyContact' => $this->sanitize($this->post('emergencyContact')),
                'emergencyPhone'   => $this->sanitize($this->post('emergencyPhone')),
                'bloodGroup'       => $this->sanitize($this->post('bloodGroup')),
                'skills'           => array_filter(array_map('trim', explode(',', $this->post('skills')))),
                'bio'              => $this->sanitize($this->post('bio')),
                'nationalId'       => $this->sanitize($this->post('nationalId')),
                'bankAccount'      => $this->sanitize($this->post('bankAccount')),
            ];

            if (!empty($_FILES['profileImage']['tmp_name'])) {
                $url = CloudinaryConfig::upload($_FILES['profileImage']['tmp_name']);
                if ($url) $data['profileImage'] = $url;
            }

            $this->userModel->updateById($id, $data);
            $this->setFlash('success', 'Employee updated successfully');
            $this->redirect('admin/users/detail/' . $id);
        }

        $this->view('admin/user-form', ['user' => $user, 'action' => 'edit', 'error' => null, 'positions' => $positions]);
    }

    public function detail(string $id): void {
        $this->requireRole('admin');
        $user = $this->userModel->findById($id);
        if (!$user) { $this->redirect('admin/users'); }
        $this->view('admin/employee-detail', ['user' => $user, 'flash' => $this->getFlash()]);
    }

    public function deleteAadhar(string $id): void {
        $this->requireRole('admin');
        $this->userModel->updateById($id, ['aadhar' => []]);
        $this->setFlash('success', 'Aadhar card removed');
        $this->redirect('admin/users/detail/' . $id);
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
            // Password change
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

            // Aadhar upload
            if ($this->post('upload_aadhar')) {
                $aadharData = $user['aadhar'] ?? [];
                foreach (['aadharFront' => 'front', 'aadharBack' => 'back'] as $fileKey => $side) {
                    if (!empty($_FILES[$fileKey]['tmp_name'])) {
                        $url = CloudinaryConfig::upload($_FILES[$fileKey]['tmp_name']);
                        if ($url) $aadharData[$side] = $url;
                    }
                }
                // Lock once both sides uploaded
                if (!empty($aadharData['front']) && !empty($aadharData['back'])) {
                    $aadharData['locked'] = true;
                }
                $this->userModel->updateById($_SESSION['user_id'], ['aadhar' => $aadharData]);
                $this->setFlash('success', 'Aadhar card uploaded successfully');
                $this->redirect($rolePrefix . '/profile');
                return;
            }

            // Aadhar delete — only admin allowed via profile (employee/intern cannot)
            if ($this->post('delete_aadhar') && $rolePrefix === 'admin') {
                $this->userModel->updateById($_SESSION['user_id'], ['aadhar' => []]);
                $this->setFlash('success', 'Aadhar card removed');
                $this->redirect($rolePrefix . '/profile');
                return;
            }

            // Editable fields update (employee/intern: phone, address, emergency only)
            $data = [
                'phone'            => $this->sanitize($this->post('phone')),
                'address'          => $this->sanitize($this->post('address')),
                'emergencyContact' => $this->sanitize($this->post('emergencyContact')),
                'emergencyPhone'   => $this->sanitize($this->post('emergencyPhone')),
            ];
            if ($rolePrefix === 'admin') {
                $data['name'] = $this->sanitize($this->post('name'));
            }
            if (!empty($_FILES['profileImage']['tmp_name'])) {
                $url = CloudinaryConfig::upload($_FILES['profileImage']['tmp_name']);
                if ($url) {
                    $data['profileImage'] = $url;
                    $_SESSION['image']    = $url;
                }
            }
            $this->userModel->updateById($_SESSION['user_id'], $data);
            $this->setFlash('success', 'Profile updated successfully');
            $this->redirect($rolePrefix . '/profile');
        }

        $this->view($rolePrefix . '/profile', ['user' => $user, 'flash' => $this->getFlash()]);
    }
}
