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

    public function findById($id)
    {
        echo $this->query_builder->select()->from($this->model->getTable())->where('id', '=', $id)->and('name', '=', 'ali')->or('age', '>', 18)->get();
    }
    public function findAll(array $filters = [])
    {
        $query = $this->query_builder->select($filters)->from($this->model->getTable())->get();
        echo $query;
    }
    public function findBy(array $criteria) {}
    public function create(array $data)
    {
        echo $this->query_builder->insert($this->model->getTable(), $data)->get();
    }
    public function update($id, array $data)
    {
       echo $this->query_builder->update($this->model->getTable(), $data)->where('id', '=', $id)->get();
    }
    public function delete($id) {
        echo $this->query_builder->delete()->from($this->model->getTable())->where('id','=',2)->get();
    }
    public function exists($id) {}
    public function count(array $filters = []) {}
}
