<?php


namespace App\Domain\AccessToken\Contracts;


interface AccessTokenServiceInterface
{
    public function all($columns = ['*']);
    public function delete($id);
    public function byId($id, $columns = ['*']);
    public function findByField(string $field, $value, $columns = ['*']);
    public function create(array $data);
    public function findByApi(string $api);
    public function update($id, array $data);
}
