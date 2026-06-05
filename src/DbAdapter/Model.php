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
}
