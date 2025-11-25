<?php

namespace Nece\Framework\Adapter\Database;

use Illuminate\Support\Facades\DB;
use Nece\Framework\Adapter\Contract\DataBase\DbRepository;
use Nece\Framework\Adapter\Contract\DataBase\IModel;
use Nece\Framework\Adapter\Contract\DataBase\IQuery;
use Nece\Framework\Adapter\Contract\DataBase\IRepository;

abstract class Repository extends DbRepository implements IRepository
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
    public function createModel(): IModel
    {
        $class = $this->getModelName();
        return new $class();
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

    /**
     * 加载实体（填充聚合根数据）
     *
     * @author nece001@163.com
     * @create 2025-11-22 20:49:10
     *
     * @param AggregateRoot $aggregateRoot
     * @return IModel
     */
    protected function loadEntity($aggregateRoot): IModel
    {
        $model = $this->getModelName()::find($aggregateRoot->getId());
        if ($model) {
            $aggregateRoot->updateData($model->toArray());
        }

        return $model;
    }

    /**
     * 保存聚合根（创建或更新）
     *
     * @author nece001@163.com
     * @create 2025-11-22 20:50:00
     *
     * @param AggregateRoot $aggregateRoot
     * @return IModel
     */
    protected function saveEntity($aggregateRoot): IModel
    {
        $model = $this->getModelName()::find($aggregateRoot->getId());
        if (!$model) {
            $model = $this->createModel();
        }

        $data = $aggregateRoot->toArray();
        foreach ($data as $key => $value) {
            if (!in_array($key, [$model->getKeyName(), $model->getCreatedAtColumn(), $model->getUpdatedAtColumn(), $model->getDeletedAtColumn()])) {
                $model->$key = $value;
            }
        }
        $model->save();

        $aggregateRoot->setId($model->{$model->getKeyName()});

        return $model;
    }

    /**
     * 删除聚合根（根据聚合根ID）
     *
     * @author nece001@163.com
     * @create 2025-11-22 20:50:39
     *
     * @param AggregateRoot $aggregateRoot
     * @return IModel
     */
    protected function deleteEntity($aggregateRoot): IModel
    {
        $model = $this->getModelName()::find($aggregateRoot->getId());
        if ($model) {
            $model->delete();
        }
        return $model;
    }
}
