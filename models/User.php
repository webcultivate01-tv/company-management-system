<?php
require_once __DIR__ . '/BaseModel.php';

class User extends BaseModel {
    protected string $collectionName = 'users';

    public function findByEmail(string $email): ?array {
        $result = $this->collection->findOne(['email' => strtolower(trim($email))]);
        return $result ? (array)$result : null;
    }

    public function createUser(array $data): ?string {
        if ($this->findByEmail($data['email'])) {
            return null; // duplicate
        }
        $data['email']        = strtolower(trim($data['email']));
        $data['password']     = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        $data['profileImage'] = $data['profileImage'] ?? '';
        $data['isActive']     = true;
        $data['role']         = in_array($data['role'], ['admin','employee','intern']) ? $data['role'] : 'employee';
        return $this->insertOne($data);
    }

    public function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }

    public function updatePassword(string $id, string $newPassword): bool {
        $hash = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);
        return $this->updateById($id, ['password' => $hash]);
    }

    public function getAllUsers(bool $includeAdmin = false): array {
        $filter = $includeAdmin ? [] : ['role' => ['$ne' => 'admin']];
        return $this->findAll($filter, ['sort' => ['createdAt' => -1]]);
    }

    public function toggleActive(string $id, bool $status): bool {
        return $this->updateById($id, ['isActive' => $status]);
    }

    public function getActiveUsers(): array {
        return $this->findAll(['isActive' => true, 'role' => ['$ne' => 'admin']]);
    }
}
