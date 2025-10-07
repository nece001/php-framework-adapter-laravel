<?php

namespace Nece\Framework\Adapter\Database;

use Closure;
use Nece\Framework\Adapter\Contract\DataBase\IDbManater;
use think\facade\Db as FacadeDb;

/**
 * @see \think\DbManager
 * @mixin \think\DbManager
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
     * @return QueryBuilder
     */
    public function table($table, $as = null)
    {
        return FacadeDb::table($table, $as);
    }

    /**
     * 原始表达式
     *
     * @param  mixed  $value
     * @return Expression
     */
    public function raw($value)
    {
        return FacadeDb::raw($value);
    }

    /**
     * 查询单条记录
     *
     * @param  string  $query
     * @param  array  $bindings
     * @param  bool  $useReadPdo
     * @return mixed
     */
    public function selectOne($query, $bindings = [], $useReadPdo = true)
    {
        return FacadeDb::selectOne($query, $bindings, $useReadPdo);
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
    public function scalar($query, $bindings = [], $useReadPdo = true)
    {
        return FacadeDb::scalar($query, $bindings, $useReadPdo);
    }

    /**
     * 查询多条记录
     *
     * @param  string  $query
     * @param  array  $bindings
     * @param  bool  $useReadPdo
     * @return array
     */
    public function select($query, $bindings = [], $useReadPdo = true)
    {
        return FacadeDb::select($query, $bindings, $useReadPdo);
    }

    /**
     * 查询多条记录并返回生成器
     *
     * @param  string  $query
     * @param  array  $bindings
     * @param  bool  $useReadPdo
     * @return \Generator
     */
    public function cursor($query, $bindings = [], $useReadPdo = true)
    {
        return FacadeDb::cursor($query, $bindings, $useReadPdo);
    }

    /**
     * 执行插入SQL语句
     *
     * @param  string  $query
     * @param  array  $bindings
     * @return bool
     */
    public function insert($query, $bindings = [])
    {
        return FacadeDb::insert($query, $bindings);
    }

    /**
     * 执行更新SQL语句
     *
     * @param  string  $query
     * @param  array  $bindings
     * @return int
     */
    public function update($query, $bindings = [])
    {
        return FacadeDb::update($query, $bindings);
    }

    /**
     * 执行删除SQL语句
     *
     * @param  string  $query
     * @param  array  $bindings
     * @return int
     */
    public function delete($query, $bindings = [])
    {
        return FacadeDb::delete($query, $bindings);
    }

    /**
     * 执行SQL语句并返回布尔结果
     *
     * @param  string  $query
     * @param  array  $bindings
     * @return bool
     */
    public function statement($query, $bindings = [])
    {
        return FacadeDb::statement($query, $bindings);
    }

    /**
     * 执行SQL语句并返回受影响的行数
     *
     * @param  string  $query
     * @param  array  $bindings
     * @return int
     */
    public function affectingStatement($query, $bindings = [])
    {
        return FacadeDb::affectingStatement($query, $bindings);
    }

    /**
     * 执行原始SQL语句
     *
     * @param  string  $query
     * @return bool
     */
    public function unprepared($query)
    {
        return FacadeDb::unprepared($query);
    }

    /**
     * 准备SQL语句绑定参数
     *
     * @param  array  $bindings
     * @return array
     */
    public function prepareBindings(array $bindings)
    {
        return FacadeDb::prepareBindings($bindings);
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
    public function transaction(Closure $callback, $attempts = 1)
    {
        return FacadeDb::transaction($callback, $attempts);
    }

    /**
     * 开始数据库事务
     *
     * @return void
     */
    public function beginTransaction()
    {
        FacadeDb::beginTransaction();
    }

    /**
     * 提交当前数据库事务
     *
     * @return void
     */
    public function commit()
    {
        FacadeDb::commit();
    }

    /**
     * 回滚当前数据库事务
     *
     * @return void
     */
    public function rollBack()
    {
        FacadeDb::rollBack();
    }

    /**
     * 获取当前数据库事务层级
     *
     * @return int
     */
    public function transactionLevel()
    {
        return FacadeDb::transactionLevel();
    }

    /**
     * 执行给定回调函数在"dry run"模式下
     *
     * @param  \Closure  $callback
     * @return array
     */
    public function pretend(Closure $callback)
    {
        return FacadeDb::pretend($callback);
    }

    /**
     * 获取当前数据库连接的数据库名
     *
     * @return string
     */
    public function getDatabaseName()
    {
        return FacadeDb::getDatabaseName();
    }
}
