<?php

namespace Nece\Framework\Adapter\Database;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Nece\Framework\Adapter\Contract\DataBase\IModel;

class Model extends EloquentModel implements IModel
{
    const DELETED_AT = 'deleted_time';

    // 指定使用的查询类
    protected static string $builder = Query::class;

    /**
     * 开始事务
     *
     * @author nece001@163.com
     * @create 2025-10-05 11:13:16
     *
     * @return void
     */
    public function startTrans(): void
    {
        $this->db->startTrans();
    }

    /**
     * 提交事务
     *
     * @author nece001@163.com
     * @create 2025-10-05 11:13:16
     *
     * @return void
     */
    public function commit(): void
    {
        $this->db->commit();
    }

    /**
     * 回滚事务
     *
     * @author nece001@163.com
     * @create 2025-10-05 11:13:16
     *
     * @return void
     */
    public function rollback(): void
    {
        $this->db->rollback();
    }

    /**
     * 获取表名
     *
     * @author nece001@163.com
     * @create 2025-10-05 11:13:16
     *
     * @return string
     */
    public function getTableName(): string
    {
        return $this->getTable();
    }

    /**
     * 表名
     * 重写父类方法，解决表名复数问题
     *
     * @author nece001@163.com
     * @create 2025-09-13 22:35:32
     *
     * @return string
     */
    public function getTable()
    {
        return $this->table ?? Str::snake(class_basename($this));
    }

    /**
     * 别名
     *
     * @author nece001@163.com
     * @create 2025-10-07 15:13:40
     *
     * @param string $alias
     * @return Query
     */
    public static function alias(string $alias)
    {
        $model = new static();
        $model->from($model->getTable(), $alias);
        return $model->query();
    }
}
