<?php

namespace Nece\Framework\Adapter\Facade;

use Nece\Framework\Adapter\Contract\Facade\Env as ContractFacadeEnv;

class Env implements ContractFacadeEnv
{
    /**
     * 获取环境变量
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        return env($key, $default);
    }

    /**
     * 设置环境变量
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function set($key, $value)
    {
        putenv($key . '=' . $value);
        $_ENV[$key] = $value;
    }

    /**
     * 判断环境变量是否存在
     *
     * @param string $key
     * @return boolean
     */
    public static function has($key): bool
    {
        return !is_null(env($key));
    }

    /**
     * 获取应用环境
     *
     * @return string
     */
    public static function getAppEnv(): string
    {
        return app()->environment();
    }

    /**
     * 获取应用根目录
     *
     * @return string
     */
    public static function getRootPath(): string
    {
        return base_path();
    }
}