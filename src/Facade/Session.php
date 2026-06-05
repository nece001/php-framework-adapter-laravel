<?php

namespace Nece\Framework\Adapter\Facade;

use Illuminate\Support\Facades\Session as FacadesSession;
use Nece\Framework\Adapter\Contract\Facade\Session as ContractFacadeSession;

class Session extends FacadesSession implements ContractFacadeSession
{
    /**
     * 销毁会话
     *
     * @return void
     */
    public static function destroy(): void
    {
        static::invalidate();
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
        static::put($key, $value);
    }

    /**
     * 删除会话属性
     *
     * @param string $key 属性键名
     * @return void
     */
    public static function delete(string $key): void
    {
        static::forget($key);
    }
}