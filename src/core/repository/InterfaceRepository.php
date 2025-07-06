<?php

namespace App\Core\Repository;

interface InterfaceRepository
{
    public function findAll();
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function findBy(array $criteria);
    public function exists($id);
    public function count(array $filters = []);
}
