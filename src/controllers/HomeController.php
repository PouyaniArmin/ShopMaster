<?php


namespace App\Controllers;

use App\Core\Application;
use App\Core\Controller;
use App\Core\Database\DatabaseConnection;
use App\Core\Database\SqliteStrategy;
use App\Core\Request;
use App\Core\ViewEngine\BaseView;
use ViewManager;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        return $this->view('home');
    }
    public function test($id)
    {
        return "test: $id";
    }
}
