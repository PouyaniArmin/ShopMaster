<?php

namespace App\Core;

use App\Core\Router\Route;

class Application{
    public Route $router;
    public Request $request; 
    public static Application $app;
    public string $route_path;
    public function __construct(string $basePath)
    {
        $this->route_path=$basePath;
        $this->request=new Request;
        $this->router=new Route($this->request);
        self::$app=$this;
    }  
}