<?php


namespace App\Core\Database;

use App\Core\Application;
use Dotenv\Dotenv;
use Exception;
use PDO;
use PDOException;
use PhpParser\Node\Expr\FuncCall;

class DatabaseConnection
{
    private static ?DatabaseConnection $db_connection = null;
    private DatabaseStrategyInterface $strategy;
    private PDO $pdo;
    private function __construct() {}
    public static function getInstance(): DatabaseConnection
    {
        if (self::$db_connection === null) {
            self::$db_connection = new self();
        }
        return self::$db_connection;
    }

    public function setSterategy(DatabaseStrategyInterface $strategy): void
    {
        $this->strategy = $strategy;
    }

    public function connect(): PDO
    {
        if ($this->strategy === null) {
            throw new Exception("No database strategy set.");
        }
        return $this->strategy->connect();
    }

    private function __wakeup()
    {
        throw new Exception("Can'ot wakeup DB class");
    }
    private function __clone()
    {
        return throw new Exception("Unserialization is not allowed.");
    }
}
