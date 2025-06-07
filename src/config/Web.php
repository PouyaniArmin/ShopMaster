<?php

namespace App\Config;

use App\Controllers\HomeController;
use App\Core\Application;

class Web
{
    public Application $app;
    public function __construct(string $root)
    {
        $this->app = new Application($root);
        $this->registerRoutes();
        // call method run call RouteMatcges  later ititlaziton routes path 
        $this->app->router->routeMatches->run();
    }

    public function registerRoutes(): void
    {
        $this->app->router->get('/', [HomeController::class, 'index']);
        $this->app->router->get('/home/{id}', [HomeController::class, 'test']);
        $this->app->router->get('/home', function () {
            return "Hello World";
        });
    }
}
