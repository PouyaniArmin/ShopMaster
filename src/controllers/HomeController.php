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
       $repository=new Repository(new UserModel);
        // $repository->findAll();
        // $repository->findById(5);
        // $repository->create(['name'=>'armin','age'=>18]);
        // $repository->update(5,['name'=>'armin','age'=>18]);
        $repository->delete(5);
       return $this->view('home');
    }
    public function test($id)
    {
        return "test: $id";
    }
}
