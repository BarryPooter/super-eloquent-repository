<?php namespace RKooistra\SuperEloquentRepository\Abstracts;

use RKooistra\SuperEloquentRepository\IsResourceRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class ResourceRepository
 * @package App\Abstracts
 */
abstract class ConcreteResourceRepository implements IsResourceRepository
{
    /** @var Model */
    protected $model;

    /** @var string */
    protected $connection = 'mysql';

    /** @return string */
    public function getConnection()
    {
        return $this->connection;
    }

    /** @param string $connection */
    public function setConnection($connection)
    {
        $this->connection = $connection;
    }

    /** @return string <Return the full path to the class here.> */
    abstract protected function getModelClass(): string;

    /** @throws \Exception*/
    public function getModel(): Builder
    {
        if (empty($this->model) || !is_string($this->model)) {
            $this->_setModel();
        }

        /** @noinspection PhpUndefinedMethodInspection */
        return (new $this->model)->on($this->getConnection());
    }

    /** @return void */
    private function _setModel(): void {
        $this->model = $this->getModelClass();
    }

    /**
     * @param array|null $selectKeys
     * @param array|null $relations
     * @return mixed
     * @throws \Exception
     */
    public function getAll(array $selectKeys = null, array $relations = null) {
        $query = $this->getModel();
        if (!empty($selectKeys)) {
            $query->select($selectKeys);
        }

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->get();
    }

    /**
     * @param string $key
     * @param string $value
     * @param array|null $selectKeys
     * @param array|null $relations
     * @return mixed
     * @throws \Exception
     */
    public function findByKey(
        string $key,
        string $value,
        array $selectKeys = null,
        array $relations = null
    ): Collection {
        $query = $this->getModel();

        if (!empty($relations)) $query = $query->with($relations);
        if (!empty($selectKeys)) $query = $query->select($selectKeys);

        return $query->where(
            $key, $value
        )->get();
    }

    /**
     * @param string $key
     * @param array $array
     * @param array|null $selectKeys
     * @return mixed
     * @throws \Exception
     */
    public function findByKeyWhereIn(
        string $key,
        array $array,
        array $selectKeys = null
    ): Collection {
        $query = (empty($selectKeys))
            ? $this->getModel()->whereIn($key, $array)
            : $this->getModel()->whereIn($key, $array)->select($selectKeys);

        return $query->get();
    }

    /**
     * @param string $key
     * @param array $array
     * @param array|null $selectKeys
     * @return mixed
     * @throws \Exception
     */
    public function findByKeyWhereNotIn(
        string $key,
        array $array,
        array $selectKeys = null
    ): Collection {
        $query = (empty($selectKeys))
            ? $this->getModel()->whereNotIn($key, $array)
            : $this->getModel()->whereNotIn($key, $array)->select($selectKeys);

        return $query->get();
    }

    /**
     * @param int $id
     * @param array|null $selectKeys
     * @return mixed
     * @throws \Exception
     */
    public function findOrFail(int $id, array $selectKeys = null): Model {
        return (empty($selectKeys))
            ? $this->getModel()->findOrFail($id)
            : $this->getModel()->select($selectKeys)->findOrFail($id);
    }

    /**
     * @param array $selectKeys
     * @param array $relations
     * @return Collection
     * @throws \Exception
     */
    public function getAllResources (array $selectKeys = [], array $relations = []): Collection {
        $query = $this->getModel();

        if (!empty($relations)) $query->with($relations);
        if (!empty($selectKeys)) $query->select($selectKeys);

        return $query->get();
    }

    /**
     * @param string $whereHasRelation
     * @param $whereHasCallback
     * @param array $selectKeys
     * @param array $relations
     * @return Collection
     * @throws \Exception
     */
    public function getAllResourcesWhereHas (string $whereHasRelation, $whereHasCallback, array $selectKeys = [], array $relations = []): Collection {
        $query = $this->getModel()
            ->whereHas($whereHasRelation, $whereHasCallback);

        if (!empty($relations))
            $query = $query->with($relations);
        if (!empty($selectKeys))
            $query = $query->select($selectKeys);

        return $query->get();
    }

    /**
     * @param string $key
     * @param string $value
     * @param array|null $selectKeys
     * @param array|null $relations
     * @return Collection
     * @throws \Exception
     */
    public function findByKeyWhereLike(string $key, string $value, array $selectKeys = null, array $relations = null): Collection {
        $query = $this->getModel();

        if (!empty($relations)) $query = $query->with($relations);
        if (!empty($selectKeys)) $query = $query->select($selectKeys);

        return $query->where(
            $key, 'LIKE', $value
        )->get();
    }

    /**
     * @param int $id
     * @param array $fields
     * @return int
     * @throws \Exception
     */
    public function updateResource(int $id, array $fields): int {
        return $this->getModel()
            ->where('id', $id)
            ->update($fields);
    }


    /**
     * @param array $array
     * @return Model
     * @throws \Exception
     */
    public function createResourceByArray(array $array): Model {
        return $this->getModel()->create($array);
    }

    /**
     * @param string $selectKey
     * @param array $ids
     * @return int
     * @throws \Exception
     */
    public function destroy(string $selectKey, array $ids): int {
        if (empty($ids)) return 0;
        return $this->getModel()
            ->whereIn($selectKey, $ids)
            ->delete();
    }

    /**
     * @param string $selectKey
     * @param array $ids
     * @return int
     * @throws \Exception
     */
    public function destroyWhereNotIn(string $selectKey, array $ids): int {
        if (empty($ids)) return 0;
        return $this->getModel()
            ->whereNotIn($selectKey, $ids)
            ->delete();
    }

    /**
     * @param array $insertables
     * @param string $identifierKey
     * @param array $selectKeys
     * @param array $relations
     * @return Model
     * @throws \Exception
     */
    public function firstOrCreate(
        array $insertables,
        string $identifierKey = 'id',
        array $selectKeys = [],
        array $relations = []
    ): Model {
        $query = $this->getModel();

        if (!empty($relations)) $query = $query->with($relations);
        if (!empty($selectKeys)) $query = $query->select($selectKeys);

        try {
            return $query->where($identifierKey, $insertables[$identifierKey])
                ->firstOrFail();
        } catch (\Exception $e) {
            return $this->createResourceByArray($insertables);
        }
    }

    /**
     * @param array $insertables
     * @param string $identifierKey
     * @param array $selectKeys
     * @param array $relations
     * @return Model
     * @throws \Exception
     */
    public function updateFirstOrCreate(
        array $insertables,
        string $identifierKey = 'id',
        array $selectKeys = [],
        array $relations = []
    ): Model {
        $_resource = $this->firstOrCreate($insertables, $identifierKey, $selectKeys, $relations);
        if ($_resource->wasRecentlyCreated) {
            return $_resource;
        }
        $_updateables = [];
        foreach ($insertables as $key => $insertable) {
            if ($_resource[$key] !== $insertable) {
                $_updateables[$key] = $insertable;
            }
        }
        if (!empty($_updateables)) $_resource->update($_updateables);
        return $_resource;
    }
}

