<?php

namespace App\Core;

use App\Core\Router\Route;

class Application{
    public Route $router;
    public Request $request; 
    public static Application $app;
    public string $basePath;
    public function __construct(string $basePath)
    {
        $this->basePath=$basePath;
        $this->request=new Request;
        $this->router=new Route($this->request);
        self::$app=$this;
    }  
}