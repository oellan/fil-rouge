<?php

namespace BadBox\Modules;

use PDO;

class Database
    extends Module
{

    private static Database $_instance;
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = new PDO($_ENV["DB_DSN"], $_ENV['DB_USER'], $_ENV['DB_PASS']);
    }

    public static function getInstance(): self
    {

        return self::$_instance ?? (self::$_instance = new Database());
    }

    /**
     * @param string $statement
     * @param int $mode
     * @return array|false
     */
    public function query(string $statement, int $mode = PDO::FETCH_ASSOC)
    {
        $stmt = $this->pdo->query($statement, $mode);
        if (!$stmt) {
            return false;
        }
        return $stmt->fetchAll($mode);
    }

    /**
     * @return bool
     */
    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * @return bool
     */
    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    /**
     * @return bool
     */
    public function rollback(): bool
    {
        return $this->pdo->commit();
    }

    /**
     * @param string $statement
     * @param array $parameters
     * @return array|false
     */
    public function preparedQuery(string $statement, array $parameters = [])
    {

        $stmt = $this->pdo->prepare($statement);
        if (!$statement) {
            return false;
        }
        return $stmt->execute($parameters) ? $stmt->fetchAll() : false;
    }

    public function preparedStatement(string $statement, array $parameters = []): bool
    {

        $stmt = $this->pdo->prepare($statement);
        if (!$statement) {
            return false;
        }
        return $stmt->execute($parameters);
    }

    /**
     * @param string $statement
     * @return bool
     */
    public function execute(string $statement): bool
    {
        return $this->pdo->exec($statement);
    }
}
