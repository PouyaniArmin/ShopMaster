<?php


namespace App\Core\Utility;


interface QueryBuilderInterface
{
    public function select():self;
    public function from(string $table):self;
    public function update(string $table, array $data): self;
    public function get():string;
}
