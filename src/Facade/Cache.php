<?php

namespace Nece\Framework\Adapter\Facade;

use Illuminate\Support\Facades\Cache as FacadesCache;
use Nece\Framework\Adapter\Contract\Facade\ICache;

class Cache extends FacadesCache implements ICache
{
    /**
     * @inheritDoc
     */
    static public function get(string $key, mixed $default = null): mixed
    {
        return parent::get($key, $default);
    }

    /**
     * @inheritDoc
     */
    static public function set(string $key, mixed $value, null|int|\DateInterval $ttl = null): bool
    {
        return parent::set($key, $value, $ttl);
    }

    /**
     * @inheritDoc
     */
    static public function delete(string $key): bool
    {
        return parent::delete($key);
    }

    /**
     * @inheritDoc
     */
    static public function clear(): bool
    {
        return parent::clear();
    }

    /**
     * @inheritDoc
     */
    static public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        return parent::getMultiple($keys, $default);
    }

    /**
     * @inheritDoc
     */
    static public function setMultiple(iterable $values, null|int|\DateInterval $ttl = null): bool
    {
        return parent::setMultiple($values, $ttl);
    }

    /**
     * @inheritDoc
     */
    static public function deleteMultiple(iterable $keys): bool
    {
        return parent::deleteMultiple($keys);
    }

    /**
     * @inheritDoc
     */
    static public function has(string $key): bool
    {
        return parent::has($key);
    }
}
