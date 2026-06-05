<?php

namespace Nece\Framework\Adapter\Facade;

use Illuminate\Support\Facades\Cache as LaravelCache;
use Nece\Framework\Adapter\Contract\Facade\Cache as ContractFacadeCache;

class Cache implements ContractFacadeCache
{
    /**
     * 获取缓存
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        return LaravelCache::get($key, $default);
    }

    /**
     * 判断缓存是否存在
     *
     * @param string $key
     * @return boolean
     */
    public static function has(string $key): bool
    {
        return LaravelCache::has($key);
    }

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
        return LaravelCache::put($key, $value, $ttl);
    }

    /**
     * 删除缓存
     *
     * @param string $key
     * @return boolean
     */
    public static function delete(string $key): bool
    {
        return LaravelCache::forget($key);
    }

    /**
     * 清空缓存
     *
     * @return boolean
     */
    public static function clear(): bool
    {
        return LaravelCache::flush();
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
        return LaravelCache::many($keys);
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
        return LaravelCache::putMany($values, $ttl);
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
            LaravelCache::forget($key);
        }
        return true;
    }
}