<?php

namespace Nece\Framework\Adapter\DbAdapter;

use Nece\Framework\Adapter\BaseModel;
use Nece\Framework\Adapter\Contract\DbAdapter\Model as DbAdapterModel;

class Model implements DbAdapterModel, \JsonSerializable, \ArrayAccess
{
    /**
     * 数据库模型
     *
     * @var BaseModel
     */
    private BaseModel $model;

    protected $alias = '';

    public function __construct(BaseModel $model)
    {
        $this->model = $model;
    }

    /**
     * 创建模型实例.
     *
     * @param string $model_name 模型名称
     *
     * @return Model
     */
    public static function instance(string $model_name): DbAdapterModel
    {
        return new self(new $model_name());
    }

    /**
     * @inheritDoc
     */
    public function setAlias(string $alias): DbAdapterModel
    {
        $this->alias = $alias;
        $this->model->setAlias($alias);
        return $this;
    }
    
    /**
     * @inheritDoc
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @inheritDoc
     */
    public function setAttr(string $name, $value): DbAdapterModel
    {
        $this->model->setAttribute($name, $value);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAttr(string $name)
    {
        $value = $this->model->getAttribute($name);
        return $this->convertModel($value);
    }

    /**
     * 递归转换模型实例为适配器的 Model 类型.
     *
     * @param mixed $value 要转换的值
     *
     * @return mixed
     */
    private function convertModel($value)
    {
        // 如果返回的是模型实例，转换为适配器的 Model 类型
        if ($value instanceof BaseModel) {
            return new Model($value);
        }

        // 如果返回的是数组或集合，遍历并递归转换其中的模型实例
        if (is_array($value) || $value instanceof \Illuminate\Support\Collection) {
            $result = [];
            foreach ($value as $item) {
                $result[] = $this->convertModel($item);
            }
            return $result;
        }

        return $value;
    }

    /**
     * @inheritDoc
     */
    public function data(array $data): DbAdapterModel
    {
        $this->model->fill($data);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getData(?string $name = null)
    {
        if ($name !== null) {
            return $this->getAttr($name);
        }
        return $this->model->getAttributes();
    }

    /**
     * @inheritDoc
     */
    public function save(array $data = []): bool
    {
        if (!empty($data)) {
            $this->model->fill($data);
        }
        return $this->model->save();
    }

    /**
     * @inheritDoc
     */
    public function forceUpdate(array $data = []): bool
    {
        if (!empty($data)) {
            $this->model->fill($data);
        }
        // 如果存在 forceSave 方法则调用，否则使用普通 save
        if (method_exists($this->model, 'forceSave')) {
            return $this->model->forceSave();
        }
        return $this->model->save();
    }

    /**
     * @inheritDoc
     */
    public function delete($data = null): bool
    {
        if ($data !== null) {
            return $this->model::destroy($data);
        }
        return $this->model->delete();
    }

    /**
     * @inheritDoc
     */
    public function getOriginal(string $name = null)
    {
        return $this->model->getOriginal($name);
    }

    /**
     * @inheritDoc
     */
    public function setKey($value): DbAdapterModel
    {
        $this->model->setKey($value);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getKey()
    {
        return $this->model->getKey();
    }

    /**
     * @inheritDoc
     */
    public function getKeyName(): string
    {
        return $this->model->getKeyName();
    }

    /**
     * @inheritDoc
     */
    public function getTable(): string
    {
        return $this->model->getTable();
    }

    /**
     * @inheritDoc
     */
    public function getModelName(): string
    {
        // 通过类名推断
        $className = get_class($this->model);
        return basename(str_replace('\\', '/', $className));
    }

    /**
     * @inheritDoc
     */
    public function getError(bool $all = false)
    {
        if (method_exists($this->model, 'getError')) {
            return $this->model->getError($all);
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    public function setError($error): DbAdapterModel
    {
        if (method_exists($this->model, 'setError')) {
            $this->model->setError($error);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function validate(array $data = [], $rules = [], array $msg = [], string $scene = ''): bool
    {
        if (method_exists($this->model, 'validate')) {
            return $this->model->validate($data, $rules, $msg, $scene);
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function autoWriteTimestamp(bool $auto = true): DbAdapterModel
    {
        // Laravel 默认自动写入时间戳，通过 $timestamps 属性控制
        $this->model->timestamps = $auto;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function query(): ModelRelationQuery
    {
        // 使用 newQuery() 方法获取查询对象
        $query = $this->model->newQuery();
        return new ModelRelationQuery($query, $this);
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return $this->model->toArray();
    }

    /**
     * 开启事务.
     *
     * @author nece001@163.com
     * @create 2026-06-04 09:14:04
     *
     * @return void
     */
    public function startTrans(): void
    {
        $this->model->getConnection()->beginTransaction();
    }

    /**
     * 提交事务.
     *
     * @author nece001@163.com
     * @create 2026-06-04 09:14:10
     *
     * @return void
     */
    public function commit(): void
    {
        $this->model->getConnection()->commit();
    }

    /**
     * 回滚事务.
     *
     * @author nece001@163.com
     * @create 2026-06-04 09:14:15
     *
     * @return void
     */
    public function rollback(): void
    {
        $this->model->getConnection()->rollBack();
    }

    /**
     * JsonSerializable 接口实现
     *
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    /**
     * ArrayAccess 接口实现 - 检查偏移量是否存在
     *
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->model->getAttributes());
    }

    /**
     * ArrayAccess 接口实现 - 获取偏移量对应的值
     *
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->getAttr($offset);
    }

    /**
     * ArrayAccess 接口实现 - 设置偏移量对应的值
     *
     * @param mixed $offset
     * @param mixed $value
     *
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->setAttr($offset, $value);
    }

    /**
     * ArrayAccess 接口实现 - 删除偏移量对应的值
     *
     * @param mixed $offset
     *
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        $attributes = $this->model->getAttributes();
        unset($attributes[$offset]);
        $this->model->fill($attributes);
    }

    /**
     * 魔术方法 - 获取属性
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->getAttr($name);
    }

    /**
     * 魔术方法 - 设置属性
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return void
     */
    public function __set(string $name, $value)
    {
        $this->setAttr($name, $value);
    }

    /**
     * 魔术方法 - 调用方法
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        if (method_exists($this->model, $name)) {
            return $this->model->$name(...$arguments);
        }

        return $this->query()->$name(...$arguments);
    }
}
