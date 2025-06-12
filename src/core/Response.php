<?php

namespace App\Core;

/**
 * Class Response
 *
 * Handles HTTP response operations such as setting status codes and headers.
 */
class Response
{
    /**
     * Stores manually set response headers.
     *
     * @var array
     */
    private array $headers = [];

    /**
     * Sets the HTTP response status code.
     *
     * @param int $code HTTP status code (e.g., 200, 404, 500).
     */
    public function setStatus(int $code)
    {
        http_response_code($code);
    }

    /**
     * Retrieves the current HTTP response status code.
     *
     * @return int The current HTTP status code.
     */
    public function getStatus(): int
    {
        return http_response_code();
    }

    /**
     * Sets a header for the HTTP response.
     *
     * @param string $name Header name (e.g., "Content-Type").
     * @param string $value Header value (e.g., "application/json").
     */
    public function setHeader(string $name, string $value)
    {
        $this->headers[$name] = $value;
        header("$name: $value", true);
    }

    /**
     * Retrieves a previously set header value.
     *
     * @param string $name Header name to retrieve.
     * @return string|null The header value if set, otherwise null.
     */
    public function getHeader(string $name): ?string
    {
        if (isset($this->headers[$name])) {
            return implode(", ", $this->headers[$name]); // Even though it's a string, kept for consistency
        }
        return null;
    }
}
