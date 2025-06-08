<?php 


namespace App\Core\Database;

use PDO;

interface DatabaseStrategyInterface{
    function connect():PDO;
    function disconnect();
}