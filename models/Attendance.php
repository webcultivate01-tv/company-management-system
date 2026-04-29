<?php
require_once __DIR__ . '/BaseModel.php';

class Attendance extends BaseModel {
    protected string $collectionName = 'attendance';

    public function getTodayRecord(string $userId): ?array {
        $today = date('Y-m-d');
        $result = $this->collection->findOne([
            'userId' => $userId,
            'date'   => $today,
        ]);
        return $result ? (array)$result : null;
    }

    public function checkIn(string $userId): array {
        $existing = $this->getTodayRecord($userId);
        if ($existing) {
            return ['success' => false, 'message' => 'Already checked in today'];
        }

        $now    = new \DateTime('now', new \DateTimeZone('Asia/Kolkata'));
        $timeStr = $now->format('H:i:s');
        $officeStart = defined('OFFICE_START_TIME') ? OFFICE_START_TIME : '09:30';
        $status = ($timeStr > $officeStart . ':00') ? 'late' : 'present';

        $id = $this->insertOne([
            'userId'     => $userId,
            'date'       => date('Y-m-d'),
            'checkIn'    => $now->format('g:i A'),
            'checkOut'   => null,
            'totalHours' => null,
            'status'     => $status,
        ]);

        return ['success' => true, 'id' => $id, 'time' => $timeStr, 'status' => $status];
    }

    public function checkOut(string $userId): array {
        $record = $this->getTodayRecord($userId);
        if (!$record) {
            return ['success' => false, 'message' => 'No check-in found for today'];
        }
        if (!empty($record['checkOut'])) {
            return ['success' => false, 'message' => 'Already checked out today'];
        }

        $now     = new \DateTime('now', new \DateTimeZone('Asia/Kolkata'));
        $timeStr = $now->format('H:i:s');

        $checkInTime  = \DateTime::createFromFormat('g:i A', $record['checkIn']) 
                        ?: \DateTime::createFromFormat('H:i:s', $record['checkIn']);
        $checkOutTime = \DateTime::createFromFormat('H:i:s', $timeStr);
        $diff         = $checkOutTime->diff($checkInTime);
        $totalHours   = round($diff->h + ($diff->i / 60), 2);

        $this->updateById((string)$record['_id'], [
            'checkOut'   => $now->format('g:i A'),
            'totalHours' => $totalHours,
        ]);

        return ['success' => true, 'time' => $timeStr, 'totalHours' => $totalHours];
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
}
