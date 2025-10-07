<?php

namespace Nece\Framework\Adapter\Database;

use Illuminate\Support\Facades\DB;
use Nece\Framework\Adapter\Contract\DataBase\IRepository;

abstract class Repository implements IRepository
{
    /**
     * @inheritDoc
     */
    public static function transaction(callable $callback)
    {
        return DB::transaction($callback);
    }

    /**
     * @inheritDoc
     */
    public static function startTrans()
    {
        return DB::beginTransaction();
    }

    /**
     * @inheritDoc
     */
    public static function commit()
    {
        return DB::commit();
    }

    /**
     * @inheritDoc
     */
    public static function rollback()
    {
        return DB::rollback();
    }
}
