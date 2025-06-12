<?php

namespace App\Core\Router;

use App\Core\Request;
use App\Core\Response;
use Closure;
use PhpParser\Node\Expr\Instanceof_;
use Reflection;
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

    protected Response $response;
    /**
     * RouteDispatcher constructor.
     * Initializes with the current request.
     *
     * @param Request $request The current HTTP request.
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Dispatches the request to the matching route callback.
     *
     * @param array|Closure $routes The collection of routes organized by HTTP method and path.
     * @return mixed|null The result of the route callback execution or null if no match found.
     */
    public function dispatch(array|Closure $routes)
    {

        if (!isset($routes[$this->request->method()])) {
            $this->response->setStatus(405);
            return "Method Not Allowed";
        }

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
        $this->response->setStatus(404);
        return "Not Found";
    }

    /**
     * Runs the route callback after optionally processing middleware.
     *
     * The $data array should contain:
     * - [0]: An array with two elements: [ControllerClassName, MethodName]
     * - 'middleware' (optional): An array where the first element is the middleware class name.
     *
     * @param array $data Route callback data and optional middleware.
     * @param array|null $params Optional parameters extracted from the route (e.g. from URL).
     * @return mixed|null Returns the middleware response if middleware denies access, 
     *                    or the controller method result, or null if method not found.
     */
    private function runRouteCallback(array $data, $params = null)
    {
        // Run middleware if set
        if (isset($data['middleware']) && !empty($data['middleware'])) {
            if (class_exists($data['middleware'][0])) {
                $middlewareReflection = new ReflectionClass($data['middleware'][0]);
                $middlewareInstance = $middlewareReflection->newInstance();
                $middlewareResult = $middlewareInstance->process($this->request, $this->response);
                if ($middlewareResult !== null) {
                    return $middlewareResult;
                }
            }
        }
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
        $args = [];
        foreach ($method->getParameters() as $param) {
            $type = $param->getType()?->getName();
            if ($type === Request::class) {
                $args[] = $this->request;
            } elseif ($type === Response::class) {
                $args[] = $this->response;
            }
        }

        return $method->invokeArgs($instance, $args);
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
