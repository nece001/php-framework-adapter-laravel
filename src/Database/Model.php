<?php

namespace Nece\Framework\Adapter\Database;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Nece\Framework\Adapter\Contract\DataBase\IModel;

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

    protected $field = [];

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
     * 查询表
     *
     * @author nece001@163.com
     * @create 2025-10-08 11:34:30
     *
     * @param string $table
     * @param string $alias
     * @return self
     */
    public function from(string $table, string $alias = '')
    {
        $this->alias = $alias;
        return parent::from($table, $alias);
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
     * 填充字段数据
     *
     * @author nece001@163.com
     * @create 2025-10-08 11:44:13
     *
     * @param array $data
     * @return self
     */
    public function fillData(array $data): self
    {
        foreach ($this->field as $field) {
            if (isset($data[$field])) {
                $this->$field = $data[$field];
            }
        }
        return $this;
    }
}
