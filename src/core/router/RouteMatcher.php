<?php


namespace App\Core\Router;

use App\Core\Request;
use App\Core\Response;
use Closure;

/**
 * Class RouteMatcher
 * Stores the registered routes and uses RouteDispatcher to match and execute them.
 */
class RouteMatcher
{
    /**
     * @var array Stores routes organized by HTTP method and path.
     */
    public array $routes = [];

    /**
     * @var RouteDispatcher Responsible for matching routes and dispatching callbacks.
     */
    protected RouteDispatcher $route_dispatcher;

    /**
     * RouteMatcher constructor.
     * Initializes RouteDispatcher with the current HTTP request.
     *
     * @param Request $request The current HTTP request instance.
     */
    public function __construct(Request $request,Response $response)
    {
        $this->route_dispatcher = new RouteDispatcher($request,$response);
    }

    /**
     * Adds a route to the routes array.
     *
     * @param string $method HTTP method (e.g., 'get', 'post').
     * @param string $path The route path pattern.
     * @param array|Closure $callback The callback to execute when route matches.
     */
    public function addRoute(string $method, string $path, array|Closure $callback,array $middleware=[])
    {
        $this->routes[$method][$path] = [$callback,'middleware'=>$middleware];
    }

    /**
     * Runs the route dispatcher to find and execute the matching route callback.
     */
    public function run()
    {
        echo $this->route_dispatcher->dispatch($this->routes);
    }
}
