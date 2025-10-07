<?php

namespace Nece\Framework\Adapter\Facade;

use Illuminate\Support\Facades\Session as FacadesSession;
use Nece\Framework\Adapter\Contract\Facade\ISession;

class Session implements ISession
{
    /**
     * @inheritDoc
     */
    public static function destroy(): void
    {
        FacadesSession::destroy();
    }

    /**
     * @inheritDoc
     */
    public static function set(string $key, $value): void
    {
        FacadesSession::set($key, $value);
    }

    /**
     * @inheritDoc
     */
    public static function get(string $key, $default = null)
    {
        return FacadesSession::get($key, $default);
    }

    /**
     * @inheritDoc
     */
    public static function has(string $key): bool
    {
        return FacadesSession::has($key);
    }

    /**
     * @inheritDoc
     */
    public static function delete(string $key): void
    {
        FacadesSession::delete($key);
    }
}
