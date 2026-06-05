<?php

namespace Nece\Framework\Adapter\Facade;

use Illuminate\Support\Facades\Session as LaravelSession;
use Nece\Framework\Adapter\Contract\Facade\Session as ContractFacadeSession;

class Session implements ContractFacadeSession
{
    /**
     * 销毁会话
     *
     * @return void
     */
    public static function destroy(): void
    {
        LaravelSession::invalidate();
    }

    /**
     * 设置会话属性
     *
     * @param string $key 属性键名
     * @param mixed $value 属性值
     * @return void
     */
    public static function set(string $key, $value): void
    {
        LaravelSession::put($key, $value);
    }

    /**
     * 获取会话属性
     *
     * @param string $key 属性键名
     * @param mixed $default 默认值
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        return LaravelSession::get($key, $default);
    }

    /**
     * 删除会话属性
     *
     * @param string $key 属性键名
     * @return void
     */
    public static function delete(string $key): void
    {
        LaravelSession::forget($key);
    }

    /**
     * 检查会话属性是否存在
     *
     * @param string $key 属性键名
     * @return bool
     */
    public static function has(string $key): bool
    {
        return LaravelSession::has($key);
    }
}