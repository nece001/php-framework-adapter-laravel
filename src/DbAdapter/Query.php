<?php

namespace Nece\Framework\Adapter\DbAdapter;

use Closure;
use Nece\Framework\Adapter\Contract\DbAdapter\Query as DbAdapterQuery;
use Nece\Framework\Adapter\DbAdapter\Paginator;
use think\db\Query as ThinkQuery;

class Query implements DbAdapterQuery
{
    /**
     * 数据库查询
     *
     * @var ThinkQuery
     */
    protected ThinkQuery $query;

    public function __construct(ThinkQuery $query)
    {
        $this->query = $query;
    }
}
