<?php
require_once __DIR__ . '/../config/database.php';

abstract class BaseModel {
    protected \MongoDB\Collection $collection;
    protected string $collectionName;

    public function __construct() {
        $db = Database::getInstance();
        $this->collection = $db->getCollection($this->collectionName);
    }

    public function findById(string $id): ?array {
        try {
            $result = $this->collection->findOne(['_id' => new \MongoDB\BSON\ObjectId($id)]);
            return $result ? (array)$result : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function findAll(array $filter = [], array $options = []): array {
        $cursor = $this->collection->find($filter, $options);
        return iterator_to_array($cursor, false);
    }

    public function insertOne(array $data): ?string {
        $data['createdAt'] = new \MongoDB\BSON\UTCDateTime();
        $data['updatedAt'] = new \MongoDB\BSON\UTCDateTime();
        $result = $this->collection->insertOne($data);
        return (string)$result->getInsertedId();
    }

    public function updateById(string $id, array $data): bool {
        $data['updatedAt'] = new \MongoDB\BSON\UTCDateTime();
        $result = $this->collection->updateOne(
            ['_id' => new \MongoDB\BSON\ObjectId($id)],
            ['$set' => $data]
        );
        return $result->getModifiedCount() > 0;
    }

    public function deleteById(string $id): bool {
        $result = $this->collection->deleteOne(['_id' => new \MongoDB\BSON\ObjectId($id)]);
        return $result->getDeletedCount() > 0;
    }

    public function deleteMany(array $filter): int {
        $result = $this->collection->deleteMany($filter);
        return $result->getDeletedCount();
    }

    public function count(array $filter = []): int {
        return $this->collection->countDocuments($filter);
    }

    protected function toObjectId(string $id): \MongoDB\BSON\ObjectId {
        return new \MongoDB\BSON\ObjectId($id);
    }

    protected function toDateTime(\DateTime $date = null): \MongoDB\BSON\UTCDateTime {
        $ts = $date ? $date->getTimestamp() : time();
        return new \MongoDB\BSON\UTCDateTime($ts * 1000);
    }

    protected function fromDateTime(\MongoDB\BSON\UTCDateTime $dt): \DateTime {
        return $dt->toDateTime()->setTimezone(new \DateTimeZone('Asia/Kolkata'));
    }
}
