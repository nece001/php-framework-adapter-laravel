<?php

namespace Nece\Framework\Adapter\Facade;

use Illuminate\Support\Facades\Cache as FacadesCache;
use Nece\Framework\Adapter\Contract\Facade\Cache as ContractFacadeCache;

class Cache extends FacadesCache implements ContractFacadeCache
{
    /**
     * 设置缓存
     *
     * @param string $key
     * @param mixed $value
     * @param null|integer|\DateInterval|null $ttl
     * @return boolean
     */
    public static function set(string $key, mixed $value, $ttl = null): bool
    {
        return static::put($key, $value, $ttl);
    }

    /**
     * 删除缓存
     *
     * @param string $key
     * @return boolean
     */
    public static function delete(string $key): bool
    {
        return static::forget($key);
    }

    /**
     * 清空缓存
     *
     * @return boolean
     */
    public static function clear(): bool
    {
        return static::flush();
    }

    /**
     * 获取多个缓存
     *
     * @param iterable $keys
     * @param mixed $default
     * @return iterable
     */
    public static function getMultiple(iterable $keys, $default = null): iterable
    {
        return static::many($keys);
    }

    /**
     * 设置多个缓存
     *
     * @param iterable $values
     * @param null|integer|\DateInterval|null $ttl
     * @return boolean
     */
    public static function setMultiple(iterable $values, $ttl = null): bool
    {
        return static::putMany($values, $ttl);
    }

    /**
     * 删除多个缓存
     *
     * @param iterable $keys
     * @return boolean
     */
    public static function deleteMultiple(iterable $keys): bool
    {
        foreach ($keys as $key) {
            static::forget($key);
        }
        return true;
    }
}