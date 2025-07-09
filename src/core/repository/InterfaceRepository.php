<?php

namespace App\Core\Repository;

interface InterfaceRepository
{

    /**
     * Retrieve all records.
     *
     * @return array List of all records.
     */
    public function findAll(): array;

    /**
     * Find a record by its unique identifier.
     *
     * @param mixed $id The record identifier.
     * @return array The record data as an associative array.
     */
    public function findById(mixed $id): array;

    /**
     * Find records by given criteria.
     *
     * @param array $criteria Key-value pairs of conditions.
     * @return array List of matching records.
     */
    public function findBy(array $criteria): array;

    /**
     * Create a new record with given data.
     *
     * @param array $data Associative array of data to insert.
     * @return mixed The created record ID or result.
     */
    public function create(array $data);

    /**
     * Update an existing record identified by ID.
     *
     * @param mixed $id The record identifier.
     * @param array $data Associative array of updated data.
     * @return bool True on success, false otherwise.
     */
    public function update(mixed $id, array $data): bool;

    /**
     * Delete a record by its identifier.
     *
     * @param mixed $id The record identifier.
     * @return bool True if the record was deleted, false otherwise.
     */
    public function delete(mixed $id): bool;

    /**
     * Check if a record exists by its identifier.
     *
     * @param mixed $id The record identifier.
     * @return bool True if exists, false otherwise.
     */
    public function exists(mixed $id): bool;

    /**
     * Count records matching the given filters.
     *
     * @param array $filters Array of conditions to filter records.
     * @return int Number of matching records.
     */
    public function count(array $filters = []): int;
}
