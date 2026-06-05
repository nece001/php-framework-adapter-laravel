<?php

namespace Nece\Framework\Adapter\DbAdapter;

use Closure;
use Nece\Framework\Adapter\Contract\DbAdapter\Query as DbAdapterQuery;
use Nece\Framework\Adapter\DbAdapter\Paginator;
use Illuminate\Database\Eloquent\Builder;

class Query implements DbAdapterQuery
{
    /**
     * 数据库查询
     *
     * @var Builder
     */
    protected Builder $query;

    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    /**
     * @inheritDoc
     */
    public function name(string $name): DbAdapterQuery
    {
        // Laravel 使用 table() 方法指定表名
        $this->query->from($name);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function table(string $table): DbAdapterQuery
    {
        $this->query->from($table);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function alias(string $alias): DbAdapterQuery
    {
        $this->query->from($this->query->getModel()->getTable() . ' as ' . $alias);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAlias(string $table = ''): string
    {
        // Laravel Builder 没有直接获取别名的方法
        return '';
    }

    /**
     * @inheritDoc
     */
    public function field(array $field): DbAdapterQuery
    {
        $columns = [];
        foreach ($field as $key => $value) {
            if (is_numeric($key)) {
                $columns[] = $value;
            } else {
                $columns[] = $key . ' as ' . $value;
            }
        }
        $this->query->select($columns);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function fieldRaw(string $field): DbAdapterQuery
    {
        $this->query->selectRaw($field);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withoutField(array $field): DbAdapterQuery
    {
        // Laravel 没有直接排除字段的方法，需要获取所有字段再排除
        // 这里简化处理，实际使用中可能需要更复杂的实现
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function tableField(array $field, string $tableName, string $prefix = '', string $alias = ''): DbAdapterQuery
    {
        $columns = [];
        $tableAlias = $alias ?: $tableName;
        foreach ($field as $f) {
            $columns[] = ($prefix ? $prefix . '.' : $tableAlias . '.') . $f;
        }
        $this->query->addSelect($columns);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function count(string $field = '*'): int
    {
        return (int)$this->query->count($field);
    }

    /**
     * @inheritDoc
     */
    public function sum(string $field): float
    {
        return (float)$this->query->sum($field);
    }

    /**
     * @inheritDoc
     */
    public function min(string $field, bool $force = true): float
    {
        return (float)$this->query->min($field);
    }

    /**
     * @inheritDoc
     */
    public function max(string $field, bool $force = true): float
    {
        return (float)$this->query->max($field);
    }

    /**
     * @inheritDoc
     */
    public function avg(string $field): float
    {
        return (float)$this->query->avg($field);
    }

    /**
     * @inheritDoc
     */
    public function join(string $join, string $condition = null, string $type = 'INNER', array $bind = []): DbAdapterQuery
    {
        $this->query->join($join, $condition, $type);
        if ($bind) {
            $this->query->addBinding($bind, 'join');
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function leftJoin(string $join, string $condition = null, array $bind = []): DbAdapterQuery
    {
        $this->join($join, $condition, 'LEFT', $bind);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function rightJoin(string $join, string $condition = null, array $bind = []): DbAdapterQuery
    {
        $this->join($join, $condition, 'RIGHT', $bind);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function fullJoin(string $join, string $condition = null, array $bind = []): DbAdapterQuery
    {
        $this->query->crossJoin($join);
        if ($condition) {
            $this->query->whereRaw($condition);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function where($field, $op = null, $condition = null): DbAdapterQuery
    {
        $this->query->where($field, $op, $condition);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereOr($field, $op = null, $condition = null): DbAdapterQuery
    {
        $this->query->orWhere($field, $op, $condition);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereXor($field, $op = null, $condition = null): DbAdapterQuery
    {
        // Laravel 没有直接的 XOR 方法，使用 whereRaw 模拟
        if (is_array($field)) {
            $conditions = [];
            foreach ($field as $k => $v) {
                $conditions[] = $k . ' = ' . $this->query->getConnection()->getPdo()->quote($v);
            }
            $this->query->whereRaw('(' . implode(' XOR ', $conditions) . ')');
        } else {
            $this->query->whereRaw($field . ' ' . $op . ' ' . $condition . ' XOR 1');
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereNull(string $field, string $logic = 'AND'): DbAdapterQuery
    {
        if (strtoupper($logic) === 'OR') {
            $this->query->orWhereNull($field);
        } else {
            $this->query->whereNull($field);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereNotNull(string $field, string $logic = 'AND'): DbAdapterQuery
    {
        if (strtoupper($logic) === 'OR') {
            $this->query->orWhereNotNull($field);
        } else {
            $this->query->whereNotNull($field);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereIn(string $field, $condition, string $logic = 'AND'): DbAdapterQuery
    {
        if (strtoupper($logic) === 'OR') {
            $this->query->orWhereIn($field, $condition);
        } else {
            $this->query->whereIn($field, $condition);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereNotIn(string $field, $condition, string $logic = 'AND'): DbAdapterQuery
    {
        if (strtoupper($logic) === 'OR') {
            $this->query->orWhereNotIn($field, $condition);
        } else {
            $this->query->whereNotIn($field, $condition);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereLike(string $field, $condition, string $logic = 'AND'): DbAdapterQuery
    {
        if (is_array($condition)) {
            foreach ($condition as $c) {
                if (strtoupper($logic) === 'OR') {
                    $this->query->orWhere($field, 'like', $c);
                } else {
                    $this->query->where($field, 'like', $c);
                }
            }
        } else {
            if (strtoupper($logic) === 'OR') {
                $this->query->orWhere($field, 'like', $condition);
            } else {
                $this->query->where($field, 'like', $condition);
            }
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereNotLike(string $field, $condition, string $logic = 'AND'): DbAdapterQuery
    {
        if (is_array($condition)) {
            foreach ($condition as $c) {
                if (strtoupper($logic) === 'OR') {
                    $this->query->orWhere($field, 'not like', $c);
                } else {
                    $this->query->where($field, 'not like', $c);
                }
            }
        } else {
            if (strtoupper($logic) === 'OR') {
                $this->query->orWhere($field, 'not like', $condition);
            } else {
                $this->query->where($field, 'not like', $condition);
            }
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereBetween(string $field, $condition, string $logic = 'AND'): DbAdapterQuery
    {
        if (is_string($condition)) {
            $condition = explode(',', $condition);
        }
        if (strtoupper($logic) === 'OR') {
            $this->query->orWhereBetween($field, $condition);
        } else {
            $this->query->whereBetween($field, $condition);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereNotBetween(string $field, $condition, string $logic = 'AND'): DbAdapterQuery
    {
        if (is_string($condition)) {
            $condition = explode(',', $condition);
        }
        if (strtoupper($logic) === 'OR') {
            $this->query->orWhereNotBetween($field, $condition);
        } else {
            $this->query->whereNotBetween($field, $condition);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereExists($condition, string $logic = 'AND'): DbAdapterQuery
    {
        if (strtoupper($logic) === 'OR') {
            $this->query->orWhereExists($condition);
        } else {
            $this->query->whereExists($condition);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereNotExists($condition, string $logic = 'AND'): DbAdapterQuery
    {
        if (strtoupper($logic) === 'OR') {
            $this->query->orWhereNotExists($condition);
        } else {
            $this->query->whereNotExists($condition);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereFindInSet(string $field, $condition, string $logic = 'AND'): DbAdapterQuery
    {
        $value = is_array($condition) ? implode(',', $condition) : $condition;
        $sql = 'FIND_IN_SET(?, ' . $field . ')';
        if (strtoupper($logic) === 'OR') {
            $this->query->orWhereRaw($sql, [$value]);
        } else {
            $this->query->whereRaw($sql, [$value]);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereJsonContains(string $field, $condition, string $logic = 'AND'): DbAdapterQuery
    {
        if (strtoupper($logic) === 'OR') {
            $this->query->orWhereJsonContains($field, $condition);
        } else {
            $this->query->whereJsonContains($field, $condition);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereColumn(string $field1, string $operator, string $field2 = null, string $logic = 'AND'): DbAdapterQuery
    {
        if ($field2 === null) {
            // 当只有两个参数时，field1 是字段，operator 是值
            $this->query->whereColumn($field1, $operator);
        } else {
            if (strtoupper($logic) === 'OR') {
                $this->query->orWhereColumn($field1, $operator, $field2);
            } else {
                $this->query->whereColumn($field1, $operator, $field2);
            }
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereRaw(string $where, array $bind = [], string $logic = 'AND'): DbAdapterQuery
    {
        if (strtoupper($logic) === 'OR') {
            $this->query->orWhereRaw($where, $bind);
        } else {
            $this->query->whereRaw($where, $bind);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereOrRaw(string $where, array $bind = []): DbAdapterQuery
    {
        $this->query->orWhereRaw($where, $bind);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereExp(string $field, string $where, array $bind = [], string $logic = 'AND'): DbAdapterQuery
    {
        $sql = $field . ' ' . $where;
        if (strtoupper($logic) === 'OR') {
            $this->query->orWhereRaw($sql, $bind);
        } else {
            $this->query->whereRaw($sql, $bind);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereFieldRaw(string $field, $op, $condition = null, string $logic = 'AND'): DbAdapterQuery
    {
        if ($condition === null) {
            $condition = $op;
            $op = '=';
        }
        $sql = $field . ' ' . $op . ' ?';
        if (strtoupper($logic) === 'OR') {
            $this->query->orWhereRaw($sql, [$condition]);
        } else {
            $this->query->whereRaw($sql, [$condition]);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function group($group): DbAdapterQuery
    {
        $this->query->groupBy($group);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function order(string $field, string $order = ''): DbAdapterQuery
    {
        $direction = $order ?: 'asc';
        $this->query->orderBy($field, $direction);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orderRaw(string $field, array $bind = []): DbAdapterQuery
    {
        $this->query->orderByRaw($field, $bind);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orderField(string $field, array $values, string $order = ''): DbAdapterQuery
    {
        $valueList = implode(',', array_map(function ($v) {
            return is_string($v) ? "'$v'" : $v;
        }, $values));
        $sql = "FIELD($field, $valueList)";
        $direction = $order ?: 'asc';
        $this->query->orderByRaw($sql . ' ' . $direction);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orderRand(): DbAdapterQuery
    {
        $this->query->inRandomOrder();
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function limit(int $offset, int $length = null): DbAdapterQuery
    {
        if ($length === null) {
            $this->query->limit($offset);
        } else {
            $this->query->offset($offset)->limit($length);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function page(int $page, int $page_size = null): DbAdapterQuery
    {
        $page_size = $page_size ?: 15;
        $offset = ($page - 1) * $page_size;
        $this->query->offset($offset)->limit($page_size);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function find($data = null): ?Model
    {
        if ($data !== null) {
            if (is_array($data)) {
                $result = $this->query->where($data)->first();
            } else {
                $result = $this->query->find($data);
            }
        } else {
            $result = $this->query->first();
        }
        if ($result instanceof \Illuminate\Database\Eloquent\Model) {
            return new Model($result);
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    public function value(string $field, $default = null)
    {
        return $this->query->value($field) ?? $default;
    }

    /**
     * @inheritDoc
     */
    public function column(string $field, string $key = ''): array
    {
        if ($key) {
            return $this->query->pluck($field, $key)->all();
        }
        return $this->query->pluck($field)->all();
    }

    /**
     * @inheritDoc
     */
    public function select(array $data = []): Collection
    {
        if (!empty($data)) {
            $this->query->where($data);
        }
        $result = $this->query->get();
        $models = [];
        foreach ($result as $item) {
            if ($item instanceof \Illuminate\Database\Eloquent\Model) {
                $models[] = new Model($item);
            } else {
                $models[] = $item;
            }
        }
        return new Collection($models);
    }

    /**
     * @inheritDoc
     */
    public function chunk(int $size, Closure $closure, string $column = 'id', string $direction = 'asc'): bool
    {
        return $this->query->chunk($size, function ($items) use ($closure) {
            $models = [];
            foreach ($items as $item) {
                if ($item instanceof \Illuminate\Database\Eloquent\Model) {
                    $models[] = new Model($item);
                } else {
                    $models[] = $item;
                }
            }
            return $closure($models);
        }, $column);
    }

    /**
     * @inheritDoc
     */
    public function paginate(int $page = 1, int $page_size = 15, array $options = []): Paginator
    {
        $laravelPaginator = $this->query->paginate($page_size, ['*'], 'page', $page);
        $total = $laravelPaginator->total();
        $currentPage = $laravelPaginator->currentPage();
        $items = [];
        foreach ($laravelPaginator->items() as $item) {
            if ($item instanceof \Illuminate\Database\Eloquent\Model) {
                $items[] = new Model($item);
            } else {
                $items[] = $item;
            }
        }
        return new Paginator($items, $total, $currentPage, $page_size);
    }

    /**
     * @inheritDoc
     */
    public function having(string $having): DbAdapterQuery
    {
        $this->query->havingRaw($having);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function lock($lock = false): DbAdapterQuery
    {
        if ($lock === true) {
            $this->query->sharedLock();
        } elseif ($lock === 'FOR UPDATE') {
            $this->query->lockForUpdate();
        } elseif ($lock !== false) {
            $this->query->lockForUpdate();
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function cache($key = true, $expire = null, $tag = null): DbAdapterQuery
    {
        // Laravel 没有内置查询缓存，需要用户自己实现
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function cacheAlways($key = true, $expire = null, $tag = null): DbAdapterQuery
    {
        // Laravel 没有内置查询缓存
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function cacheForce($key = true, $expire = null, $tag = null): DbAdapterQuery
    {
        // Laravel 没有内置查询缓存
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function union(string $union, bool $all = false): DbAdapterQuery
    {
        if ($union instanceof Builder) {
            $this->query->union($union, $all);
        } else {
            $this->query->unionRaw($union, $all);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function unionAll(string $union): DbAdapterQuery
    {
        if ($union instanceof Builder) {
            $this->query->union($union, true);
        } else {
            $this->query->unionRaw($union, true);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function distinct(bool $distinct = true): DbAdapterQuery
    {
        $this->query->distinct($distinct);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function force(string $force): DbAdapterQuery
    {
        $this->query->useIndex($force);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function comment(string $comment): DbAdapterQuery
    {
        // Laravel 没有直接的 comment 方法
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function master(bool $readMaster = true): DbAdapterQuery
    {
        if ($readMaster) {
            $this->query->useWritePdo();
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function strict(bool $strict = true): DbAdapterQuery
    {
        // Laravel 没有直接对应的严格模式
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function when($condition, $query, $otherwise = null): DbAdapterQuery
    {
        $this->query->when($condition, $query, $otherwise);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getLastSql(): string
    {
        return $this->query->toSql();
    }

    /**
     * @inheritDoc
     */
    public function startTrans(): void
    {
        $this->query->getConnection()->beginTransaction();
    }

    /**
     * @inheritDoc
     */
    public function commit(): void
    {
        $this->query->getConnection()->commit();
    }

    /**
     * @inheritDoc
     */
    public function rollback(): void
    {
        $this->query->getConnection()->rollBack();
    }

    /**
     * @inheritDoc
     */
    public function transaction(callable $callback)
    {
        return $this->query->getConnection()->transaction($callback);
    }

    /**
     * @inheritDoc
     */
    public function update(array $data): int
    {
        return $this->query->update($data);
    }

    /**
     * @inheritDoc
     */
    public function delete($data = null): int
    {
        if ($data !== null) {
            return $this->query->whereKey($data)->delete();
        }
        return $this->query->delete();
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->query->toSql();
    }
}
