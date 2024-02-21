<?php


namespace App\Domain\AccessToken\Repositories;

use App\Domain\AccessToken\Contracts\AccessTokenRepositoryInterface;
use App\Domain\AccessToken\Models\AccessToken;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class AccessTokenRepository implements AccessTokenRepositoryInterface
{

    /**
     * @param  string[]  $columns
     * @return Builder[]|Collection
     */
    public function all($columns = ['*'])
    {
        return AccessToken::query()->get($columns);
    }

    /**
     * @param $id
     * @param  string[]  $columns
     * @return Builder|Builder[]|Collection|Model|null
     */
    public function byId($id, $columns = ['*'])
    {
        return AccessToken::query()->find($id, $columns);
    }

    /**
     * @param $id
     * @return bool|mixed|null
     */
    public function delete($id)
    {
        return AccessToken::query()->findOrFail($id)->delete();
    }

    /**
     * @param  array  $data
     * @return Builder|Model
     */
    public function create(array $data): Model|Builder
    {
        return AccessToken::query()->create($data);
    }

    /**
     * @param $id
     * @param  array  $data
     * @return bool|int
     */
    public function update($id, array $data)
    {
        return AccessToken::query()->findOrFail($id)->update($data);
    }

    /**
     * @param  string  $api
     * @param $dateNow
     * @return Builder|Model|object|null
     */
    public function findByApi(string $api, $dateNow)
    {
        return AccessToken::query()->where('api', $api)
            ->where('expires_in', '>', $dateNow)
            ->orderByDesc('created_at')
            ->first();
    }

    /**
     * @param  string  $field
     * @param $value
     * @param  string[]  $columns
     * @return Builder[]|Collection
     */
    public function findByField(string $field, $value, $columns = ['*'])
    {
        return AccessToken::query()->where($field, '=', $value)->get($columns);
    }

    /**
     * @param  string  $column
     * @param  string  $direction
     * @return Builder[]|Collection
     */
    public function orderBy(string $column, $direction = 'asc')
    {
        return AccessToken::query()->orderBy($column, $direction)->get();
    }
}
