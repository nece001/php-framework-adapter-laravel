<?php

namespace Nece\Framework\Adapter\Facade;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Nece\Framework\Adapter\Contract\Facade\Validate as ContractFacadeValidate;
use Nece\Framework\Adapter\Exception\ValidateException;

class Validate implements ContractFacadeValidate
{
    /**
     * 验证数据
     *
     * @param array $data 数据
     * @param array $validate 验证规则
     * @param array $message 错误消息
     * @param array $attributes 自定义属性名
     * @param bool  $batch 是否批量验证（false=只要有一条数据验证失败就抛异常）
     *
     * @return void
     *
     * @throws ValidateException
     */
    public static function validate(array $data, array $validate, array $message = [], array $attributes = [], bool $batch = false): void
    {
        $validator = Validator::make($data, $validate, $message, $attributes);

        if ($batch) {
            $validator->stopOnFirstFailure(false);
        }

        if ($validator->fails()) {
            $errors = $validator->errors();
            throw new ValidateException($errors->first(), $errors->all());
        }
    }
}