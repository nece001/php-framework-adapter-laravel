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
            if ($field) {
                $relation = [$relation => function ($q) use ($field) {
                    $q->selectRaw('COUNT(' . $field . ')');
                }];
            }
            $this->query->withCount($relation);
            // 如果指定了别名，需要特殊处理
            if ($name) {
                // Laravel 的 withCount 不直接支持自定义别名，这里简化处理
            }
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withSum($relation, string $field = null, string $name = null): DbAdapterModelRelationQuery
    {
        if ($field) {
            $relation = [$relation => function ($q) use ($field) {
                $q->selectRaw('SUM(' . $field . ')');
            }];
        }
        $this->query->withSum($relation, $field ?: '*');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withAvg($relation, string $field = null, string $name = null): DbAdapterModelRelationQuery
    {
        if ($field) {
            $relation = [$relation => function ($q) use ($field) {
                $q->selectRaw('AVG(' . $field . ')');
            }];
        }
        $this->query->withAvg($relation, $field ?: '*');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withMin($relation, string $field = null, string $name = null): DbAdapterModelRelationQuery
    {
        if ($field) {
            $relation = [$relation => function ($q) use ($field) {
                $q->selectRaw('MIN(' . $field . ')');
            }];
        }
        $this->query->withMin($relation, $field ?: '*');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withMax($relation, string $field = null, string $name = null): DbAdapterModelRelationQuery
    {
        if ($field) {
            $relation = [$relation => function ($q) use ($field) {
                $q->selectRaw('MAX(' . $field . ')');
            }];
        }
        $this->query->withMax($relation, $field ?: '*');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function load($relation, $callback = null): DbAdapterModelRelationQuery
    {
        if ($callback) {
            $this->query->load($relation, $callback);
        } else {
            $this->query->load($relation);
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
        $this->query->{$scopeMethod}(...$args);
        return $this;
    }
}