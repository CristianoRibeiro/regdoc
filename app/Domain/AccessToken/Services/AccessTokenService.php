<?php


namespace App\Domain\AccessToken\Services;

use App\Domain\AccessToken\Contracts\AccessTokenRepositoryInterface;
use App\Domain\AccessToken\Contracts\AccessTokenServiceInterface;
use Carbon\Carbon;
use Exception;

class AccessTokenService implements AccessTokenServiceInterface
{
    public function __construct(private AccessTokenRepositoryInterface $repository)
    {
    }

    public function create(array $data)
    {
        try {
            return $this->repository->create($data);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function findByApi(string $api)
    {
        $dateNow = Carbon::now();
        try {
            return $this->repository->findByApi($api, $dateNow);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function update($id, array $data)
    {
        try {
            return $this->repository->update($id, $data);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function all($columns = ['*'])
    {
        try {
            return $this->repository->all($columns);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function delete($id)
    {
        try {
            return $this->repository->delete($id);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function byId($id, $columns = ['*'])
    {
        try {
            return $this->repository->byId($id, $columns);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function findByField(string $field, $value, $columns = ['*'])
    {
        try {
            return $this->repository->findByField($field, $value, $columns);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
