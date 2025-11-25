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

    /**
     * 构造方法
     *
     * @author nece001@163.com
     * @create 2025-11-25 13:21:32
     *
     * @param Builder $builder Laravel的数据库查询对象
     */
    public function __construct(Builder $builder)
    {
        $this->query = $builder;
    }

    /**
     * 默认调用原查询方法
     *
     * @author nece001@163.com
     * @create 2025-11-25 13:21:00
     *
     * @param string $name
     * @param mixed $arguments
     * @return mixed
     */
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
    public function alias($alias): self
    {
        $this->query->from($this->query->getModel()->getTable(), $alias);
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
    public function paginate(int $page, int $size): array
    {
        $data = array(
            'page' => $page,
            'size' => $size,
            'total' => 0,
            'items' => []
        );

        $items = $this->query->paginate($size, ['*'], '', $page);
        if ($items) {
            $data['total'] = $items->total();
            $data['current_page'] = $items->currentPage();

            foreach ($items as $row) {
                $data['items'][] = $row;
            }
        }

        return $data;
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
        return $this->query->toSql();
    }
}
