<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use PDO;

class DatabaseTest extends TestCase
{
    private PDO $pdo;

    protected function setUp(): void
    {
        $host = getenv('DB_HOST') ?: 'db';  // GitHub ActionsではDB_HOSTを使用、ローカルではdbを使用
        $dsn = "mysql:host={$host};dbname=dev_db;charset=utf8mb4";
        $this->pdo = new PDO($dsn, 'user', 'password');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function testDatabaseConnection(): void
    {
        $stmt = $this->pdo->query("SELECT 1");
        $result = $stmt->fetchColumn();
        $this->assertEquals(1, $result);
    }

    public function testUsersTable(): void
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM users");
        $count = $stmt->fetchColumn();
        $this->assertGreaterThan(0, $count);
    }
}
