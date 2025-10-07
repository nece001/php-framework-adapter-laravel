<?php

namespace Nece\Framework\Adapter\Facade;

use Illuminate\Support\Facades\Validator;
use Nece\Framework\Adapter\Contract\Facade\IValidate;
use Nece\Framework\Adapter\Exception\Contract\ValidateException;

class Validate implements IValidate
{
    /**
     * @inheritDoc
     */
    public static function validate(array $data, array $validate, array $message = [], bool $batch = false): void
    {
        $validator = Validator::make($validate)->message($message)->batch($batch);
        if (!$validator->check($data)) {
            $error = $validator->getError();
            $message = $error;
            if (is_array($error)) {
                $message = implode(';', $error);
            }

            throw new ValidateException($message, 'value_validate_fail');
        }
    }
}
