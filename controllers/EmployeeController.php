<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Attendance.php';
require_once __DIR__ . '/../models/User.php';

class EmployeeController extends BaseController {
    private Attendance $attendanceModel;
    private User       $userModel;

    public function __construct() {
        $this->attendanceModel = new Attendance();
        $this->userModel       = new User();
    }

    public function dashboard(): void {
        $this->requireRole('employee', 'intern');

        $yearMonth = date('Y-m');
        $today     = $this->attendanceModel->getTodayRecord($_SESSION['user_id']);
        $stats     = $this->attendanceModel->getMonthlyStats($_SESSION['user_id'], $yearMonth);
        $recent    = $this->attendanceModel->getUserMonthlyAttendance($_SESSION['user_id'], $yearMonth);

        // Top performers
        $rawTop = $this->attendanceModel->getTopPerformers($yearMonth, 5);
        $topPerformers = [];
        foreach ($rawTop as $row) {
            $u = $this->userModel->findById((string)$row['_id']);
            if ($u) {
                $topPerformers[] = [
                    'name'         => $u['name'],
                    'position'     => $u['position'] ?? $u['role'],
                    'profileImage' => $u['profileImage'] ?? '',
                    'totalHours'   => round((float)$row['totalHours'], 1),
                    'totalDays'    => (int)$row['totalDays'],
                    'lateCount'    => (int)$row['lateCount'],
                ];
            }
        }

        $this->view('employee/dashboard', [
            'today'         => $today,
            'stats'         => $stats,
            'recent'        => array_slice($recent, -7),
            'yearMonth'     => $yearMonth,
            'flash'         => $this->getFlash(),
            'topPerformers' => $topPerformers,
        ]);
    }
}
