<?php

namespace Nece\Framework\Adapter\Facade;

use Illuminate\Support\Facades\Validator;
use Nece\Framework\Adapter\Contract\Facade\IValidate;
use Nece\Framework\Adapter\Contract\Exception\ValidateException;

class Validate implements IValidate
{
    /**
     * @inheritDoc
     */
    public static function validate(array $data, array $validate, array $message = [], $attribute = [], bool $batch = false): void
    {
        $validator = Validator::make($data, $validate, $message, $attribute);
        if ($validator->fails()) {
            if ($batch) {
                throw new ValidateException($validator->errors()->first());
            } else {
                throw new ValidateException(implode(', ', $validator->errors()->all()));
            }
        }
    }
}
