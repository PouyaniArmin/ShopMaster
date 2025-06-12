<?php

namespace App\Core\Router;

use App\Core\Request;
use App\Core\Response;
use App\Core\Router\RouteMatcher;
use App\Core\Router\RouterInterface;
use Closure;

/**
 * Class Route
 * Manages HTTP routes and delegates them to the RouteMatcher.
 */
class Route
{
    /**
     * @var RouteMatcher Handles route storage and matching.
     */
    public $routeMatches;

    /**
     * Route constructor.
     * Initializes the RouteMatcher with the current HTTP request.
     *
     * @param Request $request The HTTP request instance.
     */

    public function __construct(Request $request,Response $response)
    {
        $this->routeMatches = new RouteMatcher($request,$response);
    }
    /**
     * Registers a GET route.
     *
     * @param string $path The route path (e.g., '/home').
     * @param array|Closure $callback The callback to execute for this route.
     */
    public function get(string $path, array|Closure $callback,array $middleware=[]): void
    {
        $this->routeMatches->addRoute('get', $path, $callback,$middleware);
    }
    /**
     * Registers a POST route.
     *
     * @param string $path The route path (e.g., '/submit').
     * @param array|Closure $callback The callback to execute for this route.
     */
    public function post(string $path, array|Closure $callback,array $middleware=[]): void
    {
        $this->routeMatches->addRoute('post', $path, $callback);
    }
}
