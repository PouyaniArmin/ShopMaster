<?php



namespace App\Core\Middleware;

use App\Core\Request;
use App\Core\Response;

class AuthMiddleware extends BaseMiddleware
{
    public function process(Request $request, Response $response): mixed
    {
        if (!$this->isAuthenticated($request)) {
            $response->setStatus(401);
            return "Unauthorized";
        }
        $next = $this->getNext();
        if ($next) {
            return $this->process($request, $response);
        }
        return null;
    }

    private function isAuthenticated(Request $request)
    {
        return true;
    }
}
