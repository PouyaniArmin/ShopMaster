<?php

namespace App\Core;

use App\Core\Router\Route;

class Application{
    public Route $router;
    public Request $request; 
    public Response $response;
    public static Application $app;
    public string $basePath;
    public function __construct(string $basePath)
    {
        $this->basePath=$basePath;
        $this->request=new Request;
        $this->response=new Response;
        $this->router=new Route($this->request,$this->response);
        self::$app=$this;
    }  
}