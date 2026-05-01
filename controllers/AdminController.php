<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Attendance.php';
require_once __DIR__ . '/../models/Client.php';
require_once __DIR__ . '/../models/Payment.php';

class AdminController extends BaseController {
    private User       $userModel;
    private Attendance $attendanceModel;
    private Client     $clientModel;
    private Payment    $paymentModel;

    public function __construct() {
        $this->userModel       = new User();
        $this->attendanceModel = new Attendance();
        $this->clientModel     = new Client();
        $this->paymentModel    = new Payment();
    }

    public function dashboard(): void {
        $this->requireRole('admin');

        $yearMonth     = date('Y-m');
        $paymentStats  = $this->paymentModel->getStats($yearMonth);
        $clientStats   = $this->clientModel->getStats();
        $todayPresent  = $this->attendanceModel->getTeamTodayStatus();
        $teamHours     = $this->attendanceModel->getTeamMonthlyHours($yearMonth);
        $totalUsers    = $this->userModel->count(['role' => ['$ne' => 'admin'], 'isActive' => true]);
        $totalTeamHours = array_sum(array_column($teamHours, 'totalHours'));

        // Top performers: enrich with user data
        $rawTop    = $this->attendanceModel->getTopPerformers($yearMonth, 5);
        $topPerformers = [];
        foreach ($rawTop as $row) {
            $u = $this->userModel->findById((string)$row['_id']);
            if ($u) {
                $topPerformers[] = [
                    'name'        => $u['name'],
                    'position'    => $u['position'] ?? $u['role'],
                    'profileImage'=> $u['profileImage'] ?? '',
                    'totalHours'  => round((float)$row['totalHours'], 1),
                    'totalDays'   => (int)$row['totalDays'],
                    'lateCount'   => (int)$row['lateCount'],
                ];
            }
        }

        $this->view('admin/dashboard', [
            'flash'          => $this->getFlash(),
            'paymentStats'   => $paymentStats,
            'clientStats'    => $clientStats,
            'todayPresent'   => count($todayPresent),
            'totalUsers'     => $totalUsers,
            'totalTeamHours' => round($totalTeamHours, 2),
            'yearMonth'      => $yearMonth,
            'topPerformers'  => $topPerformers,
        ]);
    }
}
