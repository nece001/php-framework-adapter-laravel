<?php

namespace Nece\Framework\Adapter\Facade;

use Illuminate\Support\Env;
use Illuminate\Support\Facades\Config as FacadesConfig;
use Nece\Framework\Adapter\Contract\Facade\IConfig;

class Config implements IConfig
{
    /**
     * @inheritDoc
     */
    public function config(string $key, $default = null)
    {
        return FacadesConfig::get($key, $default);
    }

    /**
     * @inheritDoc
     */
    public function env(string $key, $default = null)
    {
        return Env::get($key, $default);
    }
}
