<?php

namespace App\Core;

use Dotenv\Util\Str;

class Request
{
    /**
     * Get the current request URI path without query string.
     *
     * @return string
     */
    public function path(): string
    {
        $path = $_SERVER['REQUEST_URI'];
        $position = strpos($path, "?");
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }
        return $path;
    }

    /**
     * Get the HTTP request method in lowercase (e.g. get, post).
     *
     * @return string
     */
    public function method(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Check if the request method is GET.
     *
     * @return bool
     */
    public function isGet(): bool
    {
        return $this->method() === 'get';
    }

    /**
     * Check if the request method is POST.
     *
     * @return bool
     */
    public function isPost(): bool
    {
        return $this->method() === 'post';
    }

    /**
     * Retrieve all input data from GET or POST, sanitized.
     *
     * @return array
     */
    public function all(): array
    {
        $data = [];
        if ($this->isGet()) {
            foreach ($_GET as $key => $value) {
                $data[$key] = $this->sanitize($value);
            }
        }
        if ($this->isPost()) {
            foreach ($_POST as $key => $value) {
                $data[$key] = $this->sanitize($value);
            }
        }
        return $data;
    }

    /**
     * Get a specific input value by key from GET or POST, with a default fallback.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function input(string $key, $default = null): mixed
    {
        $all = $this->all();
        if (array_key_exists($key, $all)) {
            return $all[$key];
        }
        return $default;
    }

    /**
     * Get a sanitized GET parameter by key or return default if not present.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function hasGet(string $key, $default): mixed
    {
        return array_key_exists($key, $_GET) ? $this->sanitize($_GET[$key]) : $default;
    }

    /**
     * Get a sanitized POST parameter by key or return default if not present.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function hasPost(string $key, $default): mixed
    {
        return array_key_exists($key, $_POST) ? $this->sanitize($_POST[$key]) : $default;
    }

    /**
     * Retrieve input from specified method (GET or POST), sanitized, or default value.
     *
     * @param string $method HTTP method ('get' or 'post')
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getForm(string $method, string $key, $default)
    {
        $data = [];
        switch (strtolower($method)) {
            case 'get':
                $data = $_GET;
                break;
            case 'post':
                $data = $_POST;
                break;
            default:
                $data = [];
        }
        return array_key_exists($key, $data) ? $this->sanitize($data[$key]) : $default;
    }

    /**
     * Retrieve all uploaded files that were successfully uploaded.
     *
     * @return array
     */
    public function allFile(): array
    {
        $data = [];
        foreach ($_FILES as $key => $file) {
            if (isset($file['error']) && $file['error'] === UPLOAD_ERR_OK) {
                // Check if the file was actually uploaded via HTTP POST
                is_uploaded_file($file['tmp_name']);
            }
            $data[$key] = $file;
        }
        return $data;
    }

    /**
     * Get the raw input data (e.g. for JSON payloads).
     * If content type is JSON, attempt to decode it into an array.
     *
     * @return mixed
     */
    public function inputRaw(): mixed
    {
        $raw = file_get_contents('php://input');
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (strpos($contentType, 'application/json') !== false) {
            $decode = json_decode($raw, true);
            return $decode != null ? $decode : $raw;
        }
        return $raw;
    }

    /**
     * Sanitize input data recursively, handling arrays.
     *
     * @param mixed $value
     * @return mixed
     */
    private function sanitize(mixed $value): mixed
    {
        if (is_array($value)) {
            return array_map([$this, 'sanitize'], $value);
        }
        return filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }
}
