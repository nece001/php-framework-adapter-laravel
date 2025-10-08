<?php

namespace Nece\Framework\Adapter\Database;

use Nece\Framework\Adapter\Contract\DataBase\IQuery;
use Nece\Gears\Dto;
use Nece\Gears\PagingCollection;
use Nece\Gears\PagingVar;
use Illuminate\Database\Eloquent\Builder;

/**
 * 查询类
 *
 * @author nece001@163.com
 * @create 2025-10-08 10:09:19
 * 
 * @see Illuminate\Database\Eloquent\Builder
 */
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

    public function field($columns = ['*']): self
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
    public function fetch(): array
    {
        $list = array();
        $items = $this->get();
        if ($items) {
            foreach ($items as $item) {
                $list[] = new Dto($item->toArray());
            }
        }
        return $list;
    }
}
