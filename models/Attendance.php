<?php
require_once __DIR__ . '/BaseModel.php';

class Attendance extends BaseModel {
    protected string $collectionName = 'attendance';

    public function getTodayRecord(string $userId): ?array {
        $today = date('Y-m-d');
        $result = $this->collection->findOne(['userId' => $userId, 'date' => $today]);
        return $result ? (array)$result : null;
    }

    public function checkIn(string $userId): array {
        $now         = new \DateTime('now', new \DateTimeZone('Asia/Kolkata'));
        $timeStr     = $now->format('g:i A');
        $officeStart = defined('OFFICE_START_TIME') ? OFFICE_START_TIME : '09:30';
        $existing    = $this->getTodayRecord($userId);

        if (!$existing) {
            // First check-in of the day
            $status = ($now->format('H:i:s') > $officeStart . ':00') ? 'late' : 'present';
            $this->insertOne([
                'userId'     => $userId,
                'date'       => date('Y-m-d'),
                'status'     => $status,
                'totalHours' => 0,
                'sessions'   => [['in' => $timeStr, 'out' => null, 'hours' => null]],
            ]);
            return ['success' => true, 'time' => $timeStr, 'status' => $status];
        }

        $sessions = (array)($existing['sessions'] ?? []);
        $sessions = array_map(fn($s) => (array)$s, $sessions);

        // Block if last session is still open (not checked out)
        $last = end($sessions);
        if ($last && empty($last['out'])) {
            return ['success' => false, 'message' => 'Please check out first before checking in again'];
        }

        // Block more than 2 sessions
        if (count($sessions) >= 2) {
            return ['success' => false, 'message' => 'Maximum 2 check-ins per day reached'];
        }

        // Second check-in
        $sessions[] = ['in' => $timeStr, 'out' => null, 'hours' => null];
        $this->updateById((string)$existing['_id'], ['sessions' => $sessions]);
        return ['success' => true, 'time' => $timeStr, 'status' => $existing['status']];
    }

    public function checkOut(string $userId): array {
        $record = $this->getTodayRecord($userId);
        if (!$record) {
            return ['success' => false, 'message' => 'No check-in found for today'];
        }

        $sessions = array_map(fn($s) => (array)$s, (array)($record['sessions'] ?? []));
        $openIdx  = null;
        foreach ($sessions as $i => $s) {
            if (empty($s['out'])) { $openIdx = $i; break; }
        }

        if ($openIdx === null) {
            return ['success' => false, 'message' => 'No open check-in to check out from'];
        }

        $now      = new \DateTime('now', new \DateTimeZone('Asia/Kolkata'));
        $timeStr  = $now->format('g:i A');
        $inTime   = \DateTime::createFromFormat('g:i A', $sessions[$openIdx]['in']);
        $diff     = $now->diff($inTime);
        $sesHours = round($diff->h + ($diff->i / 60), 2);

        $sessions[$openIdx]['out']   = $timeStr;
        $sessions[$openIdx]['hours'] = $sesHours;

        $totalHours = array_sum(array_column($sessions, 'hours'));

        $this->updateById((string)$record['_id'], [
            'sessions'   => $sessions,
            'totalHours' => round($totalHours, 2),
        ]);

        return ['success' => true, 'time' => $timeStr, 'totalHours' => round($totalHours, 2)];
    }

    public function getUserMonthlyAttendance(string $userId, string $yearMonth): array {
        $pattern = '^' . preg_quote($yearMonth);
        return $this->findAll([
            'userId' => $userId,
            'date'   => ['$regex' => new \MongoDB\BSON\Regex($yearMonth, '')],
        ], ['sort' => ['date' => 1]]);
    }

    public function getAllAttendance(array $filter = []): array {
        return $this->findAll($filter, ['sort' => ['date' => -1, 'checkIn' => -1]]);
    }

    public function getMonthlyStats(string $userId, string $yearMonth): array {
        $records = $this->getUserMonthlyAttendance($userId, $yearMonth);
        $totalDays  = count($records);
        $totalHours = array_sum(array_column($records, 'totalHours'));
        $lateCount  = count(array_filter($records, fn($r) => ($r['status'] ?? '') === 'late'));

        return [
            'totalDays'  => $totalDays,
            'totalHours' => round($totalHours, 2),
            'lateCount'  => $lateCount,
        ];
    }

    public function getTeamTodayStatus(): array {
        return $this->findAll(['date' => date('Y-m-d')]);
    }

    public function getTeamMonthlyHours(string $yearMonth): array {
        $pipeline = [
            ['$match' => ['date' => ['$regex' => new \MongoDB\BSON\Regex($yearMonth, '')]]],
            ['$group' => [
                '_id'        => '$userId',
                'totalHours' => ['$sum' => '$totalHours'],
                'totalDays'  => ['$sum' => 1],
            ]],
        ];
        $cursor = $this->collection->aggregate($pipeline);
        return iterator_to_array($cursor, false);
    }

    public function getTopPerformers(string $yearMonth, int $limit = 5): array {
        $pipeline = [
            ['$match' => ['date' => ['$regex' => new \MongoDB\BSON\Regex($yearMonth, '')]]],
            ['$group' => [
                '_id'        => '$userId',
                'totalHours' => ['$sum' => '$totalHours'],
                'totalDays'  => ['$sum' => 1],
                'lateCount'  => ['$sum' => ['$cond' => [['$eq' => ['$status', 'late']], 1, 0]]],
            ]],
            ['$sort' => ['totalHours' => -1, 'totalDays' => -1]],
            ['$limit' => $limit],
        ];
        $cursor = $this->collection->aggregate($pipeline);
        return iterator_to_array($cursor, false);
    }
}
