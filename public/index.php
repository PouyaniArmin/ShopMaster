<?php

use App\Config\Web;
use Dotenv\Dotenv;

require_once __DIR__ . "/../vendor/autoload.php";

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$web = new Web(__DIR__);
