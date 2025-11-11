<?php

namespace Nece\Framework\Adapter\Database;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Nece\Framework\Adapter\Contract\DataBase\IQuery;
use Nece\Gears\PagingCollection;
use Nece\Gears\PagingVar;

/**
 * 查询类
 *
 * @author nece001@163.com
 * @create 2025-10-08 10:09:19
 * 
 * @see Illuminate\Database\Eloquent\Builder
 */
class Query implements IQuery
{
    /**
     * 数据库查询对象
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    private $query;

    /**
     * 表别名
     *
     * @var string
     */
    private $alias = '';

    public function __construct(Builder $builder)
    {
        $this->query = $builder;
    }

    public function __call($name, $arguments)
    {
        $result = $this->query->$name(...$arguments);
        if ($result instanceof Builder) {
            return $this;
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getQuery()
    {
        return $this->query;
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
    public function field($field): self
    {
        $this->query->select($field);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function join($table, Closure $on, string $type = 'INNER'): self
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
    public function leftJoin($table, Closure $on): self
    {
        $this->join($table, $on, 'LEFT');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function rightJoin($table, Closure $on): self
    {
        $this->join($table, $on, 'RIGHT');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function fullJoin($table, Closure $on): self
    {
        $this->join($table, $on, 'FULL');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function crossJoin($table, Closure $on): self
    {
        $this->join($table, $on, 'CROSS');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function order($field, string $order = 'asc'): self
    {
        $this->query->orderBy($field, $order);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function group($field): self
    {
        $this->query->groupBy($field);
        return $this;
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
    public function select(): array
    {
        $list = array();
        $items = $this->query->get();
        if ($items) {
            foreach ($items as $row) {
                $list[] = $row;
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
                $list->add($row);
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
}
