<?php

namespace Nece\Framework\Adapter\Facade;

use Illuminate\Support\Facades\Config as LaravelConfig;
use Nece\Framework\Adapter\Contract\Facade\Config as FacadeConfig;

class Config implements FacadeConfig
{
    /**
     * 获取配置变量值
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        return LaravelConfig::get($key, $default);
    }
}