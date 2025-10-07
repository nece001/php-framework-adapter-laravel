<?php

namespace Nece\Framework\Adapter;

use Nece\Framework\Adapter\Middleware\RequestIsJson;

class LaravelPhpServiceProvider extends ServiceProvider
{
    /**
     * 注册服务
     *
     * @author nece001@163.com
     * @create 2025-10-06 23:15:54
     * 
     * @return void
     */
    public function register()
    {
        // $this->app->middleware->add(RequestIsJson::class);
    }

    public function boot() {}
}
