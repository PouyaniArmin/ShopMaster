<?php

namespace App\Core\Router;

use App\Core\Request;
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
    public $routeMathecs;

    /**
     * Route constructor.
     * Initializes the RouteMatcher with the current HTTP request.
     *
     * @param Request $request The HTTP request instance.
     */

    public function __construct(Request $request)
    {
        $this->routeMathecs = new RouteMatcher($request);
    }
    /**
     * Registers a GET route.
     *
     * @param string $path The route path (e.g., '/home').
     * @param array|Closure $callback The callback to execute for this route.
     */
    public function get(string $path, array|Closure $callback): void
    {
        $this->routeMathecs->addRoute('get', $path, $callback);
    }
    /**
     * Registers a POST route.
     *
     * @param string $path The route path (e.g., '/submit').
     * @param array|Closure $callback The callback to execute for this route.
     */
    public function post(string $path, array|Closure $callback): void
    {
        $this->routeMathecs->addRoute('post', $path, $callback);
    }
}
