<?php

namespace App\Core;


class Request
{
    public function path(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

    public function method(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
}
