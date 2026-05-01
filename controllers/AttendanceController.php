<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Attendance.php';
require_once __DIR__ . '/../models/User.php';

class AttendanceController extends BaseController {
    private Attendance $attendanceModel;
    private User       $userModel;

    public function __construct() {
        $this->attendanceModel = new Attendance();
        $this->userModel       = new User();
    }

    // Employee: check in
    public function checkIn(): void {
        $this->requireAuth();
        $result = $this->attendanceModel->checkIn($_SESSION['user_id']);
        if ($result['success']) {
            $this->setFlash('success', 'Checked in at ' . $result['time'] . ' (' . ucfirst($result['status']) . ')');
        } else {
            $this->setFlash('error', $result['message']);
        }
        $this->redirect('employee/dashboard');
    }

    // Employee: check out
    public function checkOut(): void {
        $this->requireAuth();
        $result = $this->attendanceModel->checkOut($_SESSION['user_id']);
        if ($result['success']) {
            $this->setFlash('success', 'Checked out at ' . $result['time'] . '. Total today: ' . $result['totalHours'] . ' hrs');
        } else {
            $this->setFlash('error', $result['message']);
        }
        $this->redirect('employee/dashboard');
    }

    // Employee: view own attendance
    public function myAttendance(): void {
        $this->requireAuth();
        $yearMonth = $this->get('month', date('Y-m'));
        $records   = $this->attendanceModel->getUserMonthlyAttendance($_SESSION['user_id'], $yearMonth);
        $stats     = $this->attendanceModel->getMonthlyStats($_SESSION['user_id'], $yearMonth);
        $today     = $this->attendanceModel->getTodayRecord($_SESSION['user_id']);

        $this->view('employee/attendance', [
            'records'   => $records,
            'stats'     => $stats,
            'today'     => $today,
            'yearMonth' => $yearMonth,
            'flash'     => $this->getFlash(),
        ]);
    }

    public function deleteRecord(string $id): void {
        $this->requireRole('admin');
        $this->attendanceModel->deleteById($id);
        $this->setFlash('success', 'Attendance record deleted');
        $this->redirect('admin/attendance');
    }

    // Admin: view single user attendance board
    public function userAttendance(string $userId): void {
        $this->requireRole('admin');
        $yearMonth = $this->get('month', date('Y-m'));
        $user      = $this->userModel->findById($userId);
        if (!$user) { $this->redirect('admin/attendance'); }

        $records = $this->attendanceModel->getUserMonthlyAttendance($userId, $yearMonth);
        $stats   = $this->attendanceModel->getMonthlyStats($userId, $yearMonth);

        // Extra stats
        $presentCount = count(array_filter($records, fn($r) => ($r['status'] ?? '') === 'present'));
        $lateCount    = count(array_filter($records, fn($r) => ($r['status'] ?? '') === 'late'));
        $avgHours     = count($records) ? round(array_sum(array_column($records, 'totalHours')) / count($records), 2) : 0;

        $this->view('admin/user-attendance', [
            'user'         => $user,
            'records'      => $records,
            'stats'        => $stats,
            'presentCount' => $presentCount,
            'lateCount'    => $lateCount,
            'avgHours'     => $avgHours,
            'yearMonth'    => $yearMonth,
        ]);
    }

    // Admin: view all attendance
    public function adminAttendance(): void {
        $this->requireRole('admin');

        $filterDate   = $this->get('date', '');
        $filterUser   = $this->get('user', '');
        $filterMonth  = $this->get('month', date('Y-m'));

        $filter = [];
        if ($filterDate)  $filter['date']   = $filterDate;
        if ($filterUser)  $filter['userId'] = $filterUser;
        elseif ($filterMonth) {
            $filter['date'] = ['$regex' => new \MongoDB\BSON\Regex('^' . preg_quote($filterMonth), '')];
        }

        $records   = $this->attendanceModel->getAllAttendance($filter);
        $users     = $this->userModel->getAllUsers();
        $teamHours = $this->attendanceModel->getTeamMonthlyHours($filterMonth);

        // Build userId → name map
        $userMap = [];
        foreach ($users as $u) {
            $userMap[(string)$u['_id']] = $u['name'];
        }

        $this->view('admin/attendance', [
            'records'     => $records,
            'users'       => $users,
            'userMap'     => $userMap,
            'teamHours'   => $teamHours,
            'filterDate'  => $filterDate,
            'filterUser'  => $filterUser,
            'filterMonth' => $filterMonth,
            'flash'       => $this->getFlash(),
        ]);
    }
}
