<?php

namespace App\Core\Router;

use App\Core\Request;
use Closure;
use PhpParser\Node\Expr\Instanceof_;
use ReflectionClass;

use function PHPUnit\Framework\callback;

/**
 * Class RouteDispatcher
 * Responsible for dispatching the incoming HTTP request to the matching route callback.
 */
class RouteDispatcher
{
    /**
     * @var Request The current HTTP request instance.
     */
    protected Request $request;

    /**
     * RouteDispatcher constructor.
     * Initializes with the current request.
     *
     * @param Request $request The current HTTP request.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Dispatches the request to the matching route callback.
     *
     * @param array|Closure $routes The collection of routes organized by HTTP method and path.
     * @return mixed|null The result of the route callback execution or null if no match found.
     */
    public function dispatch(array|Closure $routes)
    {
        foreach ($routes[$this->request->method()] as $key => $value) {

            // Check if the route contains dynamic parameters like {id}
            if (preg_match('/\{[^}]+\}/', $key)) {
                // Convert route pattern to regex with named capturing groups
                $pattern = preg_replace('/\{(\w+)\}/', '(?<$1>[^/]+)', $key);

                // Match the current request path against the route regex
                if (preg_match('#^' . $pattern . '$#', $this->request->path(), $matches)) {
                    // Filter only named parameters from matches
                    $parmas = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                    // Run the callback with extracted parameters
                    return $this->runRouteCallback($value, $parmas);
                }
            }

            // If route is static and matches the current request path
            if ($key === $this->request->path()) {
                // If the callback is a Closure, handle it directly
                if ($value[0] instanceof Closure) {
                    return $this->handleClosures($value[0]);
                }

                // Otherwise, run the callback which is a controller method
                return $this->runRouteCallback($value);
            }
        }
    }

    /**
     * Runs the route callback.
     *
     * @param array $data Callback data containing class and method.
     * @param array|null $params Optional parameters extracted from the route.
     * @return mixed|null Result of callback execution or null.
     */
    private function runRouteCallback(array $data, $params = null)
    {
        [$className, $methodName] = $data[0];

        $reflection = new ReflectionClass($className);

        // If method doesn't exist, return null
        if (!$reflection->hasMethod($methodName)) {
            return null;
        }

        // Instantiate the controller
        $instance = $reflection->newInstance();

        // Get the method to invoke
        $method = $reflection->getMethod($methodName);

        // Invoke method with parameters if provided, else with request object
        if ($params !== null) {
            return $method->invokeArgs($instance, $params);
        }
        return $method->invokeArgs($instance, [$this->request]);

    }

    /**
     * Handles Closure callbacks by simply executing and echoing the result.
     *
     * @param Closure $clouser The Closure callback to execute.
     */
    private function handleClosures(Closure $clouser)
    {
        return $clouser();
    }
}
