<?php

namespace Nece\Framework\Adapter;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BaseModel extends Model
{
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
    const DELETED_AT = 'delete_time';

    protected $alias = '';

    /**
     * 设置模型别名
     *
     * @author nece001@163.com
     * @create 2026-06-08 19:38:55
     *
     * @param string $alias
     * @return void
     */
    public function setAlias(string $alias)
    {
        $this->alias = $alias;
    }

    /**
     * 获取模型别名
     *
     * @author nece001@163.com
     * @create 2026-06-08 19:39:00
     *
     * @return void
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * 获取与模型关联的表名（不转为复数形式）
     *
     * @return string
     */
    public function getTable()
    {
        if (! isset($this->table)) {
            return str_replace('\\', '', Str::snake(class_basename($this)));
        }

        return $this->table;
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (isset($this->append) && $this->append) {
            $this->appends = $this->append; // 兼容thinkphp的属性名
        }
    }

    public function __call($name, $arguments)
    {
        // 兼容thinkphp的属性方法
        if ($this->isGetAttr($name) || $this->isSetAttr($name)) {
            $name = substr($name, 0, -5);
            return $this->$name(...$arguments);
        }

        return parent::__call($name, $arguments);
    }

    /**
     * 判断是否获取属性方法
     *
     * @author nece001@163.com
     * @create 2026-06-07 17:26:06
     *
     * @param string $name 方法名
     * @return boolean
     */
    private function isGetAttr(string $name)
    {
        $patt = '/get(.*)Attribute/';

        return preg_match($patt, $name);
    }

    /**
     * 判断是否设置属性方法
     *
     * @author nece001@163.com
     * @create 2026-06-07 17:26:06
     *
     * @param string $name 方法名
     * @return boolean
     */
    private function isSetAttr(string $name)
    {
        $patt = '/set(.*)Attribute/';

        return preg_match($patt, $name);
    }
}
