<?php


namespace App\Core\Repository;

use App\Core\Database\DatabaseConnection;
use App\Core\Database\SqliteStrategy;
use App\Core\Utility\QueryBuilder;
use App\Models\ModelInterface;
use PDO;

class Repository implements InterfaceRepository
{
    protected ModelInterface $model;
    protected ?DatabaseConnection $db;
    private PDO $pdo;
    private QueryBuilder $query_builder;

    public function __construct(ModelInterface $model)
    {
        $this->model = $model;
        $this->db = DatabaseConnection::getInstance();
        $this->db->setStrategy(new SqliteStrategy);
        $this->pdo = $this->db->connect();
        $this->query_builder = new QueryBuilder;
    }

    /**
     * Retrieve a record by its ID.
     * @param mixed $id
     * @return array The record as associative array.
     */
    public function findById(mixed $id): array
    {
        $query = $this->query_builder
            ->select()
            ->from($this->model->getTable())
            ->where('id', '=', ':id')
            ->get();
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieve all records, optionally selecting specific columns.
     * @param array $filters Columns to select, defaults to ['*'].
     * @return array
     */
    public function findAll(array $filters = []): array
    {
        $query = $this->query_builder
            ->select($filters)
            ->from($this->model->getTable())
            ->get();
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Find records by criteria with flexible where/and/or conditions.
     * @param array $criteria
     * @return array
     */
    public function findBy(array $criteria): array
    {
        $query = $this->query_builder->select()->from($this->model->getTable());

        foreach ($criteria as $index => $condition) {
            $type = strtolower($condition['type'] ?? 'where');
            $column = $condition['column'];
            $operator = $condition['operator'];
            $value = ':' . $column . $index; // unique param key to avoid collision
            if ($index === 0 && $type === 'where') {
                $query->where($column, $operator, $value);
            } elseif ($type === 'and') {
                $query->and($column, $operator, $value);
            } elseif ($type === 'or') {
                $query->or($column, $operator, $value);
            }
        }

        $sql = $query->get();
        $stmt = $this->pdo->prepare($sql);

        // Bind values uniquely
        foreach ($criteria as $index => $condition) {
            $param = ':' . $condition['column'] . $index;
            $stmt->bindValue($param, $condition['value']);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Insert a new record.
     * @param array $data
     * @return mixed Inserted ID on success or false on failure.
     */
    public function create(array $data)
    {
        $query = $this->query_builder->insert($this->model->getTable(), $data)->get();
        $stmt = $this->pdo->prepare($query);
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $success = $stmt->execute();
        if ($success) {
            return $this->pdo->lastInsertId();
        }
        return false;
    }

    /**
     * Update an existing record by ID.
     * @param mixed $id
     * @param array $data
     * @return bool True on success, false otherwise.
     */
    public function update(mixed $id, array $data): bool
    {
        $query = $this->query_builder
            ->update($this->model->getTable(), $data)
            ->where('id', '=', ':id')
            ->get();
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $id);
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        return $stmt->execute();
    }

    /**
     * Delete a record by ID.
     * @param mixed $id
     * @return bool True if deleted successfully.
     */
    public function delete(mixed $id): bool
    {
        $query = $this->query_builder
            ->delete()
            ->from($this->model->getTable())
            ->where('id', '=', ':id')
            ->get();
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(":id", $id);
        return $stmt->execute();
    }

    /**
     * Check if a record exists by ID.
     * @param mixed $id
     * @return bool True if record exists.
     */
    public function exists(mixed $id): bool
    {
        $query = $this->query_builder
            ->checkExistence()
            ->from($this->model->getTable())
            ->where('id', '=', ':id')
            ->get();
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return (bool) $stmt->fetchColumn();
    }

    /**
     * Count records matching filters.
     * @param array $filters
     * @return int Number of records.
     */
    public function count(array $filters = []): int
    {
        $query = $this->buildCountSql($filters);
        $stmt = $this->pdo->prepare($query);

        foreach ($filters as $index => $filter) {
            $param = ":" . $filter['column'] . $index;
            $stmt->bindValue($param, $filter['value']);
        }

        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    /**
     * Build COUNT SQL with dynamic filters.
     * @param array $filters
     * @return string
     */
    private function buildCountSql(array $filters = []): string
    {
        $query = $this->query_builder->checkExistence()->from($this->model->getTable());

        foreach ($filters as $index => $filter) {
            $param = ":" . $filter['column'] . $index;

            if ($index === 0 && ($filter['type'] ?? '') === 'where') {
                $query->where($filter['column'], $filter['operator'], $param);
            } else {
                if (($filter['type'] ?? '') === 'and') {
                    $query->and($filter['column'], $filter['operator'], $param);
                } elseif (($filter['type'] ?? '') === 'or') {
                    $query->or($filter['column'], $filter['operator'], $param);
                }
            }
        }

        return $query->get();
    }
}
