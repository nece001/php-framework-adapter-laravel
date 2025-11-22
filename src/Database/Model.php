<?php

namespace Nece\Framework\Adapter\Database;

use DateTimeInterface;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Nece\Framework\Adapter\Contract\DataBase\IModel;

/**
 * ORM模型
 *
 * @author nece001@163.com
 * @create 2025-11-11 20:04:30
 * 
 * @method mixed query()
 */
class Model extends EloquentModel implements IModel
{
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
    const DELETED_AT = 'delete_time';

    /**
     * 表别名
     *
     * @var string
     */
    protected $alias = '';

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
     * 加表别名
     *
     * @author nece001@163.com
     * @create 2025-10-08 11:33:47
     *
     * @param string $alias
     * @return self
     */
    public function alias($alias): self
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * 获取表别名
     *
     * @author nece001@163.com
     * @create 2025-10-08 11:34:30
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
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
    public function getTable(): string
    {
        return $this->table ?? Str::snake(class_basename($this));
    }

    /**
     * 日期格式格式化
     *
     * @author nece001@163.com
     * @create 2025-11-22 21:09:22
     *
     * @param DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
