<?php

namespace Nece\Framework\Adapter\Database;

use Illuminate\Support\Facades\DB as FacadesDB;
use Nece\Framework\Adapter\Contract\DataBase\IDbManater;
use Illuminate\Database\Query\Builder;
use Nece\Framework\Adapter\Contract\DataBase\IQuery;

/**
 * @see \Illuminate\Database\DatabaseManager
 * @mixin \Illuminate\Database\DatabaseManager
 */
class Db implements IDbManater
{
    /**
     * 获取查询构建器
     *
     * @author nece001@163.com
     * @create 2025-10-05 11:28:24
     *
     * @param string $table 表名
     * @param string $alias 别名
     * @return Builder
     */
    public static function table($table, $alias = ''): IQuery
    {
        return new Query($table, $alias);
    }

    /**
     * 原始表达式
     *
     * @param  mixed  $value
     * @return Expression
     */
    public static function raw($value)
    {
        return FacadesDB::raw($value);
    }

    /**
     * 原始函数表达式
     *
     * @author nece001@163.com
     * @create 2025-10-07 23:04:25
     *
     * @param string $func 函数名
     * @param string $field 字段名
     * @param string $alias 别名
     * @return Expression
     */
    public static function rawFunc(string $func, string $field, string $alias)
    {
        if (false !== strpos($field, '.')) {
            $field = FacadesDB::getTablePrefix() . $field;
        }
        return self::raw($func . '(' . $field . ') as ' . $alias);
    }

    /**
     * 计数函数表达式
     *
     * @author nece001@163.com
     * @create 2025-10-07 23:04:43
     *
     * @param string $field 字段名
     * @param string $alias 别名
     * @return Expression
     */
    public static function rawCount(string $field, string $alias)
    {
        return self::rawFunc('COUNT', $field, $alias);
    }

    /**
     * 求和函数表达式
     *
     * @author nece001@163.com
     * @create 2025-10-07 23:04:52
     *
     * @param string $field 字段名
     * @param string $alias 别名
     * @return Expression
     */
    public static function rawSum(string $field, string $alias)
    {
        return self::rawFunc('SUM', $field, $alias);
    }

    /**
     * 平均值函数表达式
     *
     * @author nece001@163.com
     * @create 2025-10-07 23:05:01
     *
     * @param string $field 字段名
     * @param string $alias 别名
     * @return Expression
     */
    public static function rawAvg(string $field, string $alias)
    {
        return self::rawFunc('AVG', $field, $alias);
    }

    /**
     * 最小值函数表达式
     *
     * @author nece001@163.com
     * @create 2025-10-07 23:05:10
     *
     * @param string $field 字段名
     * @param string $alias 别名
     * @return Expression
     */
    public static function rawMin(string $field, string $alias)
    {
        return self::rawFunc('MIN', $field, $alias);
    }

    /**
     * 最大值函数表达式
     *
     * @author nece001@163.com
     * @create 2025-10-07 23:05:19
     *
     * @param string $field 字段名
     * @param string $alias 别名
     * @return Expression
     */
    public static function rawMax(string $field, string $alias)
    {
        return self::rawFunc('MAX', $field, $alias);
    }

    /**
     * @inheritDoc
     */
    public static function startTrans()
    {
        FacadesDB::beginTransaction();
    }

    /**
     * @inheritDoc
     */
    public static function commit()
    {
        FacadesDB::commit();
    }

    /**
     * @inheritDoc
     */
    public static function rollback()
    {
        FacadesDB::rollback();
    }
}
