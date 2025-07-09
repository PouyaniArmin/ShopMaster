<?php


namespace App\Core\Utility;

/**
 * Interface QueryBuilderInterface
 *
 * Defines a fluent interface for building SQL queries.
 */
interface QueryBuilderInterface
{

    /**
     * Start a SELECT query with optional columns.
     *
     * @return self
     */
    public function select(): self;

    /**
     * Specify the table name for the query.
     *
     * @param string $table The table name.
     * @return self
     */
    public function from(string $table): self;

    /**
     * Add a WHERE condition to the query.
     *
     * @param string $column The column name.
     * @param string $opretor The comparison operator (e.g., '=', '>', '<').
     * @param mixed $value The value to compare.
     * @return self
     */
    public function where(string $column, string $opretor, mixed $value): self;

    /**
     * Add an AND condition to the query.
     *
     * @param string $column The column name.
     * @param string $opretor The comparison operator.
     * @param mixed $value The value to compare.
     * @return self
     */
    public function and(string $column, string $opretor, mixed $value): self;

    /**
     * Add an OR condition to the query.
     *
     * @param string $column The column name.
     * @param string $opretor The comparison operator.
     * @param mixed $value The value to compare.
     * @return self
     */
    public function or(string $column, string $opretor, mixed $value): self;

    /**
     * Create an INSERT query for the specified table with the given data.
     *
     * @param string $table The table name.
     * @param array $data Associative array of column => value pairs.
     * @return self
     */
    public function insert(string $table, array $data): self;

    /**
     * Create an UPDATE query for the specified table with the given data.
     *
     * @param string $table The table name.
     * @param array $data Associative array of column => value pairs.
     * @return self
     */
    public function update(string $table, array $data): self;

    /**
     * Start a DELETE query.
     *
     * @return self
     */
    public function delete(): self;

    /**
     * Start a COUNT query (SELECT COUNT(*)).
     *
     * @return self
     */
    public function checkExistence(): self;

    /**
     * Get the final assembled query string.
     *
     * @return string
     */
    public function get(): string;
}
