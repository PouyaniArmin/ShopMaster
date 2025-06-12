<?php

namespace App\Config;

use App\Controllers\HomeController;
use App\Core\Application;
use App\Core\Middleware\AuthMiddleware;

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
        $this->app->router->get('/', [HomeController::class, 'index'],[AuthMiddleware::class]);
        $this->app->router->get('/test', [HomeController::class, 'body']);
        $this->app->router->get('/home/{id}', [HomeController::class, 'test']);
        $this->app->router->get('/home', function () {
            return "Hello World";
        });
    }
}
