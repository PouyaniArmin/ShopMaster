<?php


namespace App\Controllers;

use App\Core\Application;
use App\Core\Controller;
use App\Core\Database\DatabaseConnection;
use App\Core\Database\SqliteStrategy;
use App\Core\Repository\Repository;
use App\Core\Request;
use App\Core\ViewEngine\BaseView;
use App\Models\UserModel;
use ViewManager;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $data = [
            ['type' => 'where', 'column' => 'id', 'operator' => '=', 'value' => 1],
            ['type' => 'and', 'column' => 'name', 'operator' => '=', 'value' => 'armin']
        ];
        $inset_data = ['name' => 'sara'];
        $filters = [
            ['type' => 'where', 'column' => 'id', 'operator' => '>', 'value' => 10],
            ['type' => 'and', 'column' => 'name', 'operator' => '=', 'value' => 'test'],
            ['type' => 'or', 'column' => 'id', 'operator' => '<', 'value' => 2]
        ];
        $repository = new Repository(new UserModel);
        return $this->view('home');
    }
    public function test($id)
    {
        return "test: $id";
    }
}
