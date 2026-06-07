<?php

namespace Nece\Framework\Adapter\DbAdapter;

use Nece\Framework\Adapter\Contract\DbAdapter\ModelRelationQuery as DbAdapterModelRelationQuery;

class ModelRelationQuery extends Query implements DbAdapterModelRelationQuery
{
    /**
     * @inheritDoc
     */
    public function with($relation, $callback = null): DbAdapterModelRelationQuery
    {
        if ($callback) {
            $this->query->with($relation, $callback);
        } else {
            $this->query->with($relation);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withJoin($relation, $callback = null, bool $joinType = false): DbAdapterModelRelationQuery
    {
        if ($joinType) {
            $this->query->withJoin($relation, $callback, 'left');
        } else {
            $this->query->withJoin($relation, $callback);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withCount($relation, string $field = null, string $name = null): DbAdapterModelRelationQuery
    {
        if (is_array($relation)) {
            // 数组形式：withCount(['posts', 'comments'])
            $this->query->withCount($relation);
        } else {
            // 字符串形式，可能带字段和别名
            if ($name) {
                // Laravel 8+ 支持自定义别名：withCount(['relation as alias'])
                $relation = [$relation . ' as ' . $name];
            }
            $this->query->withCount($relation);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withSum($relation, string $field = null, string $name = null): DbAdapterModelRelationQuery
    {
        if (!$field) {
            throw new \InvalidArgumentException('withSum requires a field parameter');
        }
        
        if ($name) {
            // Laravel 8+ 支持自定义别名
            $relation = [$relation . ' as ' . $name];
        }
        $this->query->withSum($relation, $field);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withAvg($relation, string $field = null, string $name = null): DbAdapterModelRelationQuery
    {
        if (!$field) {
            throw new \InvalidArgumentException('withAvg requires a field parameter');
        }
        
        if ($name) {
            $relation = [$relation . ' as ' . $name];
        }
        $this->query->withAvg($relation, $field);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withMin($relation, string $field = null, string $name = null): DbAdapterModelRelationQuery
    {
        if (!$field) {
            throw new \InvalidArgumentException('withMin requires a field parameter');
        }
        
        if ($name) {
            $relation = [$relation . ' as ' . $name];
        }
        $this->query->withMin($relation, $field);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withMax($relation, string $field = null, string $name = null): DbAdapterModelRelationQuery
    {
        if (!$field) {
            throw new \InvalidArgumentException('withMax requires a field parameter');
        }
        
        if ($name) {
            $relation = [$relation . ' as ' . $name];
        }
        $this->query->withMax($relation, $field);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function load($relation, $callback = null): DbAdapterModelRelationQuery
    {
        // load 是模型实例方法，不能在查询构建器上调用
        // 需要先执行查询获取模型
        $model = $this->query->first();
        if ($model) {
            if ($callback) {
                $model->load($relation, $callback);
            } else {
                $model->load($relation);
            }
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function scope(string $scope, array $args = []): DbAdapterModelRelationQuery
    {
        // 调用模型的 scope 方法，Laravel 的 scope 方法名以 scope 开头
        $scopeMethod = 'scope' . ucfirst($scope);
        if (method_exists($this->query, $scopeMethod)) {
            $this->query->{$scopeMethod}(...$args);
        }
        return $this;
    }
}