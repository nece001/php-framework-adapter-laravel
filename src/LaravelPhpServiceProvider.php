<?php

namespace Nece\Framework\Adapter;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Database\DatabaseManager;
use Illuminate\Routing\Route;
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
        // 获取 HTTP 内核实例
        $kernel = $this->app->make(Kernel::class);

        // 注册全局中间件
        $kernel->pushMiddleware(RequestIsJson::class);

        Route::macro('pattern', function () {});
    }

    public function boot() {}
}
