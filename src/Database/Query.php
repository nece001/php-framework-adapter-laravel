<?php

namespace Nece\Framework\Adapter\Database;

use Nece\Framework\Adapter\Contract\DataBase\IQuery;
use Nece\Gears\Dto;
use Nece\Gears\PagingCollection;
use Nece\Gears\PagingVar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Query extends Builder implements IQuery
{
    private $alias = '';

    public function getTable()
    {
        return $this->model->getTable();
    }

    public function getAlias()
    {
        return $this->alias;
    }

    public function field($columns = [])
    {
        $this->query->select($columns);
        return $this;
    }

    public function alias(string $alias): self
    {
        $this->alias = $alias;
        $this->query->from($this->model->getTable(), $alias);
        return $this;
    }

    public function order($column, $direction = 'asc')
    {
        $this->query->orderBy($column, $direction);
        return $this;
    }

    public function group(...$groups)
    {
        $this->query->groupBy(...$groups);
        return $this;
    }

    public function join($table, $first, $operator = null, $second = null, $type = 'inner', $where = false)
    {
        if (is_array($table)) {
            $tmp = '';
            foreach ($table as $t => $a) {
                $tmp = $t . ' as ' . $a;
            }
            $table = $tmp;
        }
        $this->query->join($table, $first, $operator, $second);
        return $this;
    }

    /**
     * 按分页查询
     *
     * @author nece001@163.com
     * @create 2025-09-14 13:27:17
     *
     * @return PagingCollection
     */
    public function paging(PagingVar $paging): PagingCollection
    {
        $page = $paging->getPage();
        $size = $paging->getPageSize();
        $list = new PagingCollection([], 0, $page, $size, $paging->getPageVarName(), $paging->getPageSizeVarName());
        $items = $this->paginate($size, ['*'], $paging->getPageVarName(), $page);
        if ($items) {
            $list->setTotal($items->total());
            $list->setCurrentPage($items->currentPage());

            foreach ($items as $item) {
                $list->add(new Dto($item->toArray()));
            }
        }

        return $list;
    }

    /**
     * 查询所有
     *
     * @author nece001@163.com
     * @create 2025-10-07 10:24:54
     *
     * @return array
     */
    public function all(): array
    {
        $list = array();
        $items = $this->select();
        if ($items) {
            foreach ($items as $item) {
                $list[] = new Dto($item->toArray());
            }
        }
        return $list;
    }
}
