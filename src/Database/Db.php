<?php

namespace Nece\Framework\Adapter\Database;

use Closure;
use Illuminate\Support\Facades\DB as FacadesDB;
use Nece\Framework\Adapter\Contract\DataBase\IDbManater;
use Illuminate\Database\Query\Builder;

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
     * @param string $as 别名
     * @return Builder
     */
    public static function table($table, $as = null)
    {
        return FacadesDB::table($table, $as);
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
     * 查询单条记录
     *
     * @param  string  $query
     * @param  array  $bindings
     * @param  bool  $useReadPdo
     * @return mixed
     */
    public static function selectOne($query, $bindings = [], $useReadPdo = true)
    {
        return FacadesDB::selectOne($query, $bindings, $useReadPdo);
    }

    /**
     * 查询单个字段值
     *
     * @param  string  $query
     * @param  array  $bindings
     * @param  bool  $useReadPdo
     * @return mixed
     *
     * @throws RuntimeException
     */
    public static function scalar($query, $bindings = [], $useReadPdo = true)
    {
        return FacadesDB::scalar($query, $bindings, $useReadPdo);
    }

    /**
     * 查询多条记录
     *
     * @param  string  $query
     * @param  array  $bindings
     * @param  bool  $useReadPdo
     * @return array
     */
    public static function select($query, $bindings = [], $useReadPdo = true)
    {
        return FacadesDB::select($query, $bindings, $useReadPdo);
    }

    /**
     * 查询多条记录并返回生成器
     *
     * @param  string  $query
     * @param  array  $bindings
     * @param  bool  $useReadPdo
     * @return \Generator
     */
    public static function cursor($query, $bindings = [], $useReadPdo = true)
    {
        return FacadesDB::cursor($query, $bindings, $useReadPdo);
    }

    /**
     * 执行插入SQL语句
     *
     * @param  string  $query
     * @param  array  $bindings
     * @return bool
     */
    public static function insert($query, $bindings = [])
    {
        return FacadesDB::insert($query, $bindings);
    }

    /**
     * 执行更新SQL语句
     *
     * @param  string  $query
     * @param  array  $bindings
     * @return int
     */
    public static function update($query, $bindings = [])
    {
        return FacadesDB::update($query, $bindings);
    }

    /**
     * 执行删除SQL语句
     *
     * @param  string  $query
     * @param  array  $bindings
     * @return int
     */
    public static function delete($query, $bindings = [])
    {
        return FacadesDB::delete($query, $bindings);
    }

    /**
     * 执行SQL语句并返回布尔结果
     *
     * @param  string  $query
     * @param  array  $bindings
     * @return bool
     */
    public static function statement($query, $bindings = [])
    {
        return FacadesDB::statement($query, $bindings);
    }

    /**
     * 执行SQL语句并返回受影响的行数
     *
     * @param  string  $query
     * @param  array  $bindings
     * @return int
     */
    public static function affectingStatement($query, $bindings = [])
    {
        return FacadesDB::affectingStatement($query, $bindings);
    }

    /**
     * 执行原始SQL语句
     *
     * @param  string  $query
     * @return bool
     */
    public static function unprepared($query)
    {
        return FacadesDB::unprepared($query);
    }

    /**
     * 准备SQL语句绑定参数
     *
     * @param  array  $bindings
     * @return array
     */
    public static function prepareBindings(array $bindings)
    {
        return FacadesDB::prepareBindings($bindings);
    }

    /**
     * 执行数据库事务
     *
     * @param  \Closure  $callback
     * @param  int  $attempts
     * @return mixed
     *
     * @throws \Throwable
     */
    public static function transaction(Closure $callback, $attempts = 1)
    {
        return FacadesDB::transaction($callback, $attempts);
    }

    /**
     * 开始数据库事务
     *
     * @return void
     */
    public static function beginTransaction()
    {
        FacadesDB::beginTransaction();
    }

    /**
     * 提交当前数据库事务
     *
     * @return void
     */
    public static function commit()
    {
        FacadesDB::commit();
    }

    /**
     * 回滚当前数据库事务
     *
     * @return void
     */
    public static function rollBack()
    {
        FacadesDB::rollBack();
    }

    /**
     * 获取当前数据库事务层级
     *
     * @return int
     */
    public static function transactionLevel()
    {
        return FacadesDB::transactionLevel();
    }

    /**
     * 执行给定回调函数在"dry run"模式下
     *
     * @param  \Closure  $callback
     * @return array
     */
    public static function pretend(Closure $callback)
    {
        return FacadesDB::pretend($callback);
    }

    /**
     * 获取当前数据库连接的数据库名
     *
     * @return string
     */
    public static function getDatabaseName()
    {
        return FacadesDB::getDatabaseName();
    }

    public static function __callStatic($name, $arguments)
    {
        return FacadesDB::$name(...$arguments);
    }
}
