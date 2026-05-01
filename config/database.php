<?php
require_once __DIR__ . '/../vendor/autoload.php';

class Database {
    private static ?Database $instance = null;
    private \MongoDB\Client $client;
    private \MongoDB\Database $db;

    // ─── CONFIGURE YOUR MONGODB ATLAS URI HERE ───────────────────────────────
    private string $uri      = 'mongodb+srv://webcultivate01_db_user:7447746247@cms.0d4hsma.mongodb.net/?retryWrites=true&w=majority&appName=CMS';
    private string $dbName   = 'company_management';
    // ─────────────────────────────────────────────────────────────────────────

    private function __construct() {
        try {
            $this->client = new \MongoDB\Client($this->uri, [], [
                'typeMap' => [
                    'array'    => 'array',
                    'document' => 'array',
                    'root'     => 'array',
                ]
            ]);
            $this->db = $this->client->selectDatabase($this->dbName);
        } catch (\Exception $e) {
            error_log('MongoDB Connection Error: ' . $e->getMessage());
            die(json_encode(['error' => 'Database connection failed']));
        }
    }

    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getDB(): \MongoDB\Database {
        return $this->db;
    }

    public function getCollection(string $name): \MongoDB\Collection {
        return $this->db->selectCollection($name);
    }
}
