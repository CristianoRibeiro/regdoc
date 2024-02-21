<?php


namespace App\Domain\AccessToken\Contracts;


interface AccessTokenRepositoryInterface
{
    public function all($columns = ['*']);
    public function byId($id, $columns = ['*']);
    public function findByField(string $field, $value, $columns = ['*']);
    public function orderBy(string $column, $direction = 'asc');
    public function findByApi(string $api, $dateNow);
    public function delete($id);
    public function create(array $data);
    public function update($id, array $data);
}
