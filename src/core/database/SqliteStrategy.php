<?php


namespace App\Core\Database;

use App\Core\Application;
use Exception;
use PDO;
use PDOException;

class SqliteStrategy implements DatabaseStrategyInterface
{

    private ?PDO $conn = null;
    public function connect(): PDO
    {
        try {
            $this->conn = new PDO('sqlite:' . dirname(Application::$app->basePath) . "/" . $_ENV['DB_DATABASE']);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception("Failed to connect to SQLite");
        }
        return $this->conn;
    }

    public function disconnect()
    {
        if ($this->conn !== null) {
            $this->conn = null;
        }
    }
}
