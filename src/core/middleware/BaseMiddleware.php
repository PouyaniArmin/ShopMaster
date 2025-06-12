<?php

namespace App\Core\Middleware;

use App\Core\Request;
use App\Core\Response;
/**
 * Abstract base class for middleware components.
 *
 * Provides the foundational structure for middleware chaining using the Chain of Responsibility pattern.
 * Each middleware can decide whether to handle the request/response or pass it to the next middleware.
 */
abstract class BaseMiddleware implements InterfaceMiddleware
{
    /**
     * @var InterfaceMiddleware|null The next middleware in the chain.
     */
    protected $next;

    /**
     * Sets the next middleware in the chain.
     *
     * @param InterfaceMiddleware $next The next middleware to execute after the current one.
     * @return void
     */
    public function setNext(InterfaceMiddleware $next)
    {
        $this->next = $next;
    }

    /**
     * Retrieves the next middleware in the chain.
     *
     * @return InterfaceMiddleware|null Returns the next middleware, or null if none is set.
     */
    public function getNext(): ?InterfaceMiddleware
    {
        return $this->next;
    }

    /**
     * Handles the request and response.
     *
     * Each middleware must implement this method to perform its logic.
     * Middleware can return a value to terminate the chain early or call the next middleware in the chain.
     *
     * @param Request $request The HTTP request object.
     * @param Response $response The HTTP response object.
     * @return mixed The result of processing, or a short-circuited response.
     */
    abstract public function process(Request $request, Response $response): mixed;
}
