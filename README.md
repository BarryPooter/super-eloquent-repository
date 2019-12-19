# Super Eloquent Repository
## Introduction
Welcome to the Super Eloquent Repository library package! This library has the simple use of eliminating having to write the same method over and over again for all your Repositories, allowing you to do the fun programming.

The package is basically a concrete superclass which you can extend all your default Eloquent repositories with. This superclass contains all basic functionalities which you would normally have to write again and again for normal CRUD methods.

## How to install and use the package
- Implement this package into your project by running `composer require rkooistra/super-eloquent-repository`.
- Create a class which extends the `RKooistra\SuperEloquentRepository\Abstracts\ConcreteResourceRepository` package.
- You must implement the `getModel()` stub per the inheritance rules; configure this to return the class of your respective model, ie:
    ```php
    /** @return string <Return the full path to the class here.> */
    protected function getModelClass(): string
    {
        return \App\Entities\User::class;
    }
    ``` 
- You should now be able to use all of the implemented methods!

## Available methods (per 19-12-2019)
```php
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
```
