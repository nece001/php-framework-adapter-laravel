<?php

namespace Nece\Framework\Adapter\Database;

use Nece\Framework\Adapter\Contract\DataBase\IQuery;
use Nece\Gears\Dto;
use Nece\Gears\PagingCollection;
use Nece\Gears\PagingVar;
use Illuminate\Database\Eloquent\Builder;


class Query extends Builder implements IQuery
{
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
        $items = $this->paginate(array('page' => $page, 'list_rows' => $size));
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
