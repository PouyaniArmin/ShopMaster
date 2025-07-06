<?php


namespace App\Core\Utility;

use App\Core\Utility\QueryTemplate as UtilityQueryTemplate;
use Dotenv\Util\Str;
use QueryTemplate;

class QueryBuilder implements QueryBuilderInterface
{
    protected string $query = '';
    private array $binding = [];
    public function select(array $column = []): self
    {
        $column = $column ?: ['*'];
        $this->query = sprintf(UtilityQueryTemplate::SELECT, implode(' ,', $column));
        return $this;
    }
    public function from(string $table): self
    {
        $this->query .= "FROM $table";
        return $this;
    }
    public function where(string $column, string $opretor, mixed $value): self
    {
        $this->query .= sprintf(UtilityQueryTemplate::WHERE, $column, $opretor, $this->escapValue($value));
        return $this;
    }
    public function and(string $column, string $opretor, mixed $value): self
    {
        $this->query .= sprintf(UtilityQueryTemplate::AND, $column, $opretor, $this->escapValue($value));
        return $this;
    }

    public function or(string $column, string $opretor, mixed $value): self
    {
        $this->query .= sprintf(UtilityQueryTemplate::OR, $column, $opretor, $this->escapValue($value));
        return $this;
    }

    // insert

    public function insert(string $table, array $data): self
    {
        $this->query .= sprintf(UtilityQueryTemplate::INSERT, $table, implode(", ", array_keys($data)), implode(", ", array_values($data)));
        return $this;
    }
    // update 

    public function update(string $table, array $data): self
    {
        $segments = array_map(
            fn($key, $value) => "$key = '" . addslashes((string) $value) . "'",
            array_keys($data),
            $data
        );
        $result=implode(', ',$segments);
        $this->query.=sprintf(UtilityQueryTemplate::UPDATE,$table,$result);
        return $this;
    }
    // delete

    public function delete():self{
        $this->query.=UtilityQueryTemplate::DELETE;
        return $this;
    }

    public function get(): string
    {
        return trim($this->query);
    }

    private function escapValue(mixed $value): string
    {
        if (is_null($value)) {
            return 'Null';
        }
        if (is_bool($value)) {
            return $value ? '1' : '0';
        }
        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }
        if (is_string($value)) {
            $escaped = addslashes($value);
            return "'$escaped'";
        }
        return "'" . addslashes((string)$value) . "'";
    }
}
