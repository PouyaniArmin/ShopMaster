<?php


namespace App\Core\Utility;

use App\Core\Utility\QueryTemplate as UtilityQueryTemplate;
use Dotenv\Util\Str;
use QueryTemplate;

class QueryBuilder implements QueryBuilderInterface
{
/**
     * Holds the SQL query being built.
     */
    protected string $query = '';

    /**
     * Starts a SELECT statement with given columns (defaults to *).
     *
     * @param array $column List of columns to select
     * @return self
     */
    public function select(array $column = []): self
    {
        $column = $column ?: ['*'];
        $this->query = sprintf(UtilityQueryTemplate::SELECT, implode(', ', $column));
        return $this;
    }

    /**
     * Appends the FROM clause with the table name.
     *
     * @param string $table Table name
     * @return self
     */
    public function from(string $table): self
    {
        $this->query .= " FROM $table";
        return $this;
    }

    /**
     * Appends a WHERE clause to the query.
     *
     * @param string $column Column name
     * @param string $opretor Comparison operator (=, >, <, etc.)
     * @param mixed $value Value or placeholder
     * @return self
     */
    public function where(string $column, string $opretor, mixed $value): self
    {
        $this->query .= sprintf(UtilityQueryTemplate::WHERE, $column, $opretor, $value);
        return $this;
    }

    /**
     * Appends an AND condition to the query.
     *
     * @param string $column Column name
     * @param string $opretor Comparison operator
     * @param mixed $value Value or placeholder
     * @return self
     */
    public function and(string $column, string $opretor, mixed $value): self
    {
        $this->query .= sprintf(UtilityQueryTemplate::AND, $column, $opretor, $value);
        return $this;
    }

    /**
     * Appends an OR condition to the query.
     *
     * @param string $column Column name
     * @param string $opretor Comparison operator
     * @param mixed $value Value or placeholder
     * @return self
     */
    public function or(string $column, string $opretor, mixed $value): self
    {
        $this->query .= sprintf(UtilityQueryTemplate::OR, $column, $opretor, $value);
        return $this;
    }

    /**
     * Builds an INSERT statement with named placeholders.
     *
     * @param string $table Table name
     * @param array $data Associative array of column => value
     * @return self
     */
    public function insert(string $table, array $data): self
    {
        // Convert keys to named placeholders like :name
        $placeholders = array_map(fn($key) => ":$key", array_keys($data));
        $this->query .= sprintf(
            UtilityQueryTemplate::INSERT,
            $table,
            implode(", ", array_keys($data)),
            implode(", ", $placeholders)
        );
        return $this;
    }

    /**
     * Builds an UPDATE statement with named placeholders.
     *
     * @param string $table Table name
     * @param array $data Associative array of column => value
     * @return self
     */
    public function update(string $table, array $data): self
    {
        // Format as column=:column for each key
        $placeholders = array_map(fn($key) => "$key=:$key", array_keys($data));
        $result = implode(', ', $placeholders);
        $this->query .= sprintf(UtilityQueryTemplate::UPDATE, $table, $result);
        return $this;
    }

    /**
     * Adds a DELETE statement to the query.
     *
     * @return self
     */
    public function delete(): self
    {
        $this->query .= UtilityQueryTemplate::DELETE;
        return $this;
    }

    /**
     * Adds a COUNT(*) statement to the query (used for existence check).
     *
     * @return self
     */
    public function checkExistence(): self
    {
        $this->query .= UtilityQueryTemplate::COUNT;
        return $this;
    }

    /**
     * Returns the final SQL query as a string.
     *
     * @return string
     */
    public function get(): string
    {
        return trim($this->query);
    }
}
