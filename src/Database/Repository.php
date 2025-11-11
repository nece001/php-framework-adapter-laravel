<?php

namespace Nece\Framework\Adapter\Database;

use Illuminate\Support\Facades\DB;
use Nece\Framework\Adapter\Contract\DataBase\IQuery;
use Nece\Framework\Adapter\Contract\DataBase\IRepository;

abstract class Repository implements IRepository
{
    /**
     * @inheritDoc
     */
    public function transaction(callable $callback)
    {
        return DB::transaction($callback);
    }

    /**
     * @inheritDoc
     */
    public function startTrans(): void
    {
        DB::beginTransaction();
    }

    /**
     * @inheritDoc
     */
    public function commit(): void
    {
        DB::commit();
    }

    /**
     * @inheritDoc
     */
    public function rollback(): void
    {
        DB::rollback();
    }

    /**
     * @inheritDoc
     */
    public function createModel(): Model
    {
        $class = $this->getModelName();
        return new $class();
    }

    public function find(array $where)
    {
        $model = $this->getModelName()::where($where)->select();
        if (!$model) {
            return null;
        }
        return $this->getEntityName()::buildFromData($model->toArray());
    }

    public function findById($id)
    {
        $model = $this->query()->find($id);
        if (!$model) {
            return null;
        }
        return $this->getEntityName()::buildFromData($model->toArray());
    }

    public function delete($entity)
    {
        $model_name = $this->getModelName();
        $model_name::destroy($entity->getId());
        $entity->emitEvents();
    }

    public function save($entity): void
    {
        $id = $entity->getId();
        $model = null;
        $query = $this->query();
        if ($id) {
            $model = $query->find($id);
        }
        if (!$model) {
            $model = $this->createModel();
        }

        $model->fill($entity->toSaveArray());
        $model->save();
        $entity->setId($model->id);
        $entity->emitEvents();
    }

    /**
     * @inheritDoc
     */
    public function query(string $alias = ''): IQuery
    {
        $query = $this->getModelName()::query();
        if ($alias) {
            $model = $query->getModel()->alias($alias);
            $query->from($model->getTable(), $alias);
        }

        return new Query($query);
    }
}
