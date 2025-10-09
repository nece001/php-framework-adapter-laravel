<?php

namespace Nece\Framework\Adapter\Database;

use Closure;
use Illuminate\Support\Facades\DB;
use Nece\Framework\Adapter\Contract\DataBase\IQuery;
use Nece\Gears\Dto;
use Nece\Gears\PagingCollection;
use Nece\Gears\PagingVar;

/**
 * 查询类
 *
 * @author nece001@163.com
 * @create 2025-10-08 10:09:19
 * 
 * @see Illuminate\Database\Query\Builder
 */
class Query implements IQuery
{
    /**
     * 数据库查询对象
     *
     * @var \Illuminate\Database\Query\Builder
     */
    private $query;

    /**
     * 表别名
     *
     * @var string
     */
    private $alias = '';

    public function __construct(string $table, string $alias = '')
    {
        $this->query = DB::table($table, $alias);
        if ($alias) {
            $this->alias = $alias;
        }
    }

    /**
     * @inheritDoc
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @inheritDoc
     */
    public function distinct(bool $distinct = true): self
    {
        $this->query->distinct($distinct);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function field(string|array $field): self
    {
        $this->query->select($field);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function join(string|array $table, Closure $on, string $type = 'INNER'): self
    {
        if (is_array($table)) {
            $key = array_key_first($table);
            $table = $key . ' AS ' . $table[$key];
        }

        $this->query->join($table, $on, $type);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function leftJoin(string|array $table, Closure $on): self
    {
        $this->join($table, $on, 'LEFT');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function rightJoin(string|array $table, Closure $on): self
    {
        $this->join($table, $on, 'RIGHT');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function fullJoin(string|array $table, Closure $on): self
    {
        $this->join($table, $on, 'FULL');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function crossJoin(string|array $table, Closure $on): self
    {
        $this->join($table, $on, 'CROSS');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function where($field, $op = null, $condition = null): self
    {
        $this->query->where($field, $op, $condition);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function order(string $field, string $direction = 'asc'): self
    {
        $this->query->orderBy($field, $direction);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function group(string $field): self
    {
        $this->query->groupBy($field);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function groupByRaw(string $group): self
    {
        $this->query->groupByRaw($group);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function having(string $field, string $operator, $value): self
    {
        $this->query->having($field, $operator, $value);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function page(int $page, int $limit): self
    {
        $this->query->forPage($page, $limit);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function limit(int $limit): self
    {
        $this->query->limit($limit);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function lock(bool $lock = true): self
    {
        $this->query->lock($lock);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function comment(string $comment): self
    {
        $this->query->comment($comment);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function union(Closure $closure): self
    {
        $this->query->union($closure);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function partition(string $partition): self
    {
        $this->query->partition($partition);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function value(string $field, $default = null)
    {
        return $this->query->value($field, $default);
    }

    /**
     * @inheritDoc
     */
    public function first()
    {
        return $this->query->first();
    }

    /**
     * @inheritDoc
     */
    public function fetch(): array
    {
        $list = array();
        $items = $this->query->get();
        if ($items) {
            foreach ($items as $row) {
                $list[] = new Dto((array)$row);
            }
        }
        return $list;
    }

    /**
     * @inheritDoc
     */
    public function paginate(PagingVar $paging): PagingCollection
    {
        $page = $paging->getPage();
        $size = $paging->getPageSize();

        $list = new PagingCollection([], 0, $page, $size, $paging->getPageVarName(), $paging->getPageSizeVarName());
        $items = $this->query->paginate($size, ['*'], $paging->getPageVarName(), $page);
        if ($items) {
            $list->setTotal($items->total());
            $list->setCurrentPage($items->currentPage());

            foreach ($items as $row) {
                $list->add(new Dto((array)$row));
            }
        }

        return $list;
    }

    /**
     * @inheritDoc
     */
    public function chunk(int $size, callable $callback, ?string $column = null, string $order = 'asc')
    {
        return $this->query->chunk($size, $callback, $column, $order);
    }

    /**
     * @inheritDoc
     */
    public function toSql(): string
    {
        return $this->query->buildSql();
    }

    /**
     * @inheritDoc
     */
    public function whereOr($field, $op = null, $condition = null): self
    {
        $this->query->orWhere($field, $op, $condition);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereXor($field, $op = null, $condition = null): self
    {
        $this->query->whereXor($field, $op, $condition);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereNull(string $field, string $logic = 'AND'): self
    {
        $this->query->whereNull($field, $logic);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereNotNull(string $field, string $logic = 'AND'): self
    {
        $this->query->whereNotNull($field, $logic);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereOrNull(string $field): self
    {
        $this->query->orWhereNull($field);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereOrNotNull(string $field): self
    {
        $this->query->orWhereNotNull($field);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereExists($condition, string $logic = 'AND'): self
    {
        $this->query->whereExists($condition, $logic);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereNotExists($condition, string $logic = 'AND'): self
    {
        $this->query->whereNotExists($condition, $logic);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereIn(string $field, $condition, string $logic = 'AND'): self
    {
        $this->query->whereIn($field, $condition, $logic);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereOrIn(string $field, $condition): self
    {
        $this->query->orWhereIn($field, $condition);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereNotIn(string $field, $condition, string $logic = 'AND'): self
    {
        $this->query->whereNotIn($field, $condition, $logic);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereOrNotIn(string $field, $condition): self
    {
        $this->query->orWhereNotIn($field, $condition);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereLike(string $field, $condition, string $logic = 'AND'): self
    {
        $this->query->whereLike($field, $condition, $logic);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereOrLike(string $field, $condition): self
    {
        $this->query->orWhereLike($field, $condition);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereNotLike(string $field, $condition, string $logic = 'AND'): self
    {
        $this->query->whereNotLike($field, $condition, $logic);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereOrNotLike(string $field, $condition): self
    {
        $this->query->orWhereNotLike($field, $condition);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereBetween(string $field, $condition, string $logic = 'AND'): self
    {
        $this->query->whereBetween($field, $condition, $logic);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereNotBetween(string $field, $condition, string $logic = 'AND'): self
    {
        $this->query->whereNotBetween($field, $condition, $logic);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereOrBetween(string $field, $condition): self
    {
        $this->query->orWhereBetween($field, $condition);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereOrNotBetween(string $field, $condition): self
    {
        $this->query->orWhereNotBetween($field, $condition);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereJsonContains(string $field, $condition, string $logic = 'AND'): self
    {
        $this->query->whereJsonContains($field, $condition, $logic);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereOrJsonContains(string $field, $condition, string $logic = 'AND'): self
    {
        $this->query->orWhereJsonContains($field, $condition, $logic);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereColumn(string $field1, string $operator, ?string $field2 = null, string $logic = 'AND'): self
    {
        $this->query->whereColumn($field1, $operator, $field2, $logic);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereOrColumn(string $field1, string $operator, ?string $field2 = null): self
    {
        $this->query->orWhereColumn($field1, $operator, $field2);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereRaw(string $where, array $bind = [], string $logic = 'AND'): self
    {
        $this->query->whereRaw($where, $bind, $logic);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereOrRaw(string $where, array $bind = [], string $logic = 'AND'): self
    {
        $this->query->orWhereRaw($where, $bind, $logic);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function when($condition, Closure | array $query, Closure | array | null $otherwise = null): self
    {
        $this->query->when($condition, $query, $otherwise);
        return $this;
    }
}
