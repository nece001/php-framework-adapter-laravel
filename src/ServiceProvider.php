<?php

namespace Nece\Framework\Adapter;

use Closure;
use Illuminate\Support\ServiceProvider as SupportServiceProvider;
use Nece\Framework\Adapter\Contract\IServiceProvider;

/**
 * 统一服务提供器命名空间
 *
 * @author nece001@163.com
 * @create 2025-09-21 17:47:43
 */
abstract class ServiceProvider extends SupportServiceProvider implements IServiceProvider
{
    /**
     * @inheritDoc
     */
    abstract public function register();

    /**
     * @inheritDoc
     */
    abstract public function boot();

    /**
     * @inheritDoc
     */
    public function bind(string|array $abstract, $concrete = null): void
    {
        $this->app->bind($abstract, $concrete);
    }

    /**
     * @inheritDoc
     */
    public function addViewNamespaces(string $namespace, string $path): void
    {
        $this->app->view->addNamespace($namespace, $path);
    }

    /**
     * @inheritDoc
     */
    public function loadRouteFile(string $filename): void
    {
        $this->loadRoutesFrom($filename);
    }

    /**
     * @inheritDoc
     */
    public function addRoutes(Closure $closure): void
    {
        // $this->registerRoutes($closure);
    }

    /**
     * @inheritDoc
     */
    public function registerCommands(array $commands): void
    {
        $this->commands($commands);
    }
}
