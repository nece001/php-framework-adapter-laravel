<?php

namespace Nece\Framework\Adapter\Facade;

use Illuminate\Container\Container as LaravelContainer;
use Illuminate\Contracts\Console\Kernel;
use Nece\Gears\IContainer;

/**
 * 容器
 * 作用：解决依赖注入的兼容问题
 *
 * @author nece001@163.com
 * @create 2025-09-13 14:35:02
 */
class Container implements IContainer
{
    /**
     * 初始化应用
     *
     * @author nece001@163.com
     * @create 2025-09-13 14:34:15
     *
     * @return void
     */
    public static function initApp(): void
    {
        $app = require_once '../../../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();
        // require_once '../../autoload.php';
    }

    /**
     * 获取应用实例
     *
     * @author nece001@163.com
     * @create 2025-09-13 14:35:08
     *
     * @return Object
     */
    public static function getApp()
    {
        return LaravelContainer::getInstance();
    }

    /**
     * 依赖注入创建实例
     *
     * @author nece001@163.com
     * @create 2025-09-13 14:35:20
     *
     * @param string $abstract
     * @param array $vars
     * @param boolean $newInstance
     * @return Object
     */
    public static function make(string $abstract, array $vars = [], bool $newInstance = false)
    {
        return LaravelContainer::getInstance()->make($abstract, $vars, $newInstance);
    }
}
