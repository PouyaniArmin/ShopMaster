<?php

namespace App\Core\Middleware;

use App\Core\Request;
use App\Core\Response;

/**
 * Interface for middleware classes.
 *
 * Any middleware class must implement this interface to be compatible with the middleware pipeline.
 * The process method is used to handle the request/response before the controller is executed.
 *
 * @param Request $request The incoming HTTP request object.
 * @param Response $response The HTTP response object to be modified or returned early.
 * @return mixed If a response is returned here, it will short-circuit the request lifecycle.
 */
interface InterfaceMiddleware
{
    public function process(Request $request, Response $response);
}
