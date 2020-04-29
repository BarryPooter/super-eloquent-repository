<?php namespace RKooistra\SuperEloquentRepository\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface IsResourceRepository
{
    public function getModel(): Builder;
    public function setConnection($connection);
    public function getAll(array $selectKeys = null, array $relations = null);
    public function findByKey(
        string $key,
        string $value,
        array $selectKeys = null,
        array $relations = null
    ): Collection;
    public function findByKeyWhereIn(
        string $key,
        array $array,
        array $selectKeys = null
    ): Collection;
    public function findByKeyWhereNotIn(
        string $key,
        array $array,
        array $selectKeys = null
    ): Collection;
    public function findOrFail(int $id, array $selectKeys = null): Model;
    public function getAllResources (array $selectKeys = [], array $relations = []): Collection;
    public function getAllResourcesWhereHas (string $whereHasRelation, $whereHasCallback, array $selectKeys = [], array $relations = []): Collection;
    public function findByKeyWhereLike(string $key, string $value, array $selectKeys = null, array $relations = null): Collection;
    public function updateResource(int $id, array $fields): int;
    public function createResourceByArray(array $array): Model;
    public function destroy(string $selectKey, array $ids): int;
    public function destroyWhereNotIn(string $selectKey, array $ids): int;
    public function firstOrCreate(array $insertables, string $identifierKey = 'id', array $selectKeys = [], array $relations = []): Model;
    public function updateFirstOrCreate(array $insertables, string $identifierKey = 'id', array $selectKeys = [], array $relations = []): Model;
}

