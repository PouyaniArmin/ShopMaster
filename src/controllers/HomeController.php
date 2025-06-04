<?php


namespace App\Controllers;

use App\Core\Request;

class HomeController{
    public function index(Request $request){
        echo "Home";
    }
    public function test($id){
        echo "test: $id";
    }
}

