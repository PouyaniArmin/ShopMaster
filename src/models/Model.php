<?php

namespace App\Models;

class Model implements ModelInterface
{
    protected string $table = '';
    public function getTable(): string
    {
        return $this->table;
    }
}
