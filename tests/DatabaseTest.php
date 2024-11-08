<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use PDO;

class DatabaseTest extends TestCase
{
    private PDO $pdo;

    protected function setUp(): void
    {
        // GitHub ActionsとDocker環境の両方に対応
        $host = getenv('GITHUB_ACTIONS') ? '127.0.0.1' : 'db';
        $dsn = "mysql:host={$host};dbname=dev_db;charset=utf8mb4";
        
        try {
            $this->pdo = new PDO($dsn, 'user', 'password');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            $this->markTestSkipped('Database connection failed: ' . $e->getMessage());
        }
    }

    /**
     * データベース接続が正常に機能しているかをテスト
     *
     * @return void
     */
    public function testDatabaseConnection(): void
    {
        $stmt = $this->pdo->query("SELECT 1");
        $result = $stmt->fetchColumn();
        $this->assertEquals(1, $result);
    }

    /**
     * usersテーブルにデータが存在することをテスト
     *
     * @return void
     */
    public function testUsersTable(): void
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM users");
        $count = $stmt->fetchColumn();
        $this->assertGreaterThan(7, $count);
    }
}
