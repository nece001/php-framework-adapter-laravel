<?php

namespace Nece\Framework\Adapter\Facade;

use Illuminate\Support\Facades\Route as FacadesRoute;
use Nece\Framework\Adapter\Contract\Facade\IRoute;

/**
 * 路由门面
 *
 * @author nece001@163.com
 * @create 2025-10-06 22:57:00
 * 
 * @implement Route<\Illuminate\Routing\Route>
 * @implement Group<\Illuminate\Routing\Route>
 */
class Route implements IRoute
{
    /**
     * 注册一个新的GET路由到路由器
     *
     * @param  string  $uri
     * @param  array|string|callable  $action
     * @return Route
     */
    public static function get($uri, $action)
    {
        $uri = self::fixRule($uri);
        return FacadesRoute::get($uri, $action);
    }

    /**
     * 注册一个新的POST路由到路由器
     *
     * @param  string  $uri
     * @param  array|string|callable  $action
     * @return Route
     */
    public static function post($uri, $action)
    {
        $uri = self::fixRule($uri);
        return FacadesRoute::post($uri, $action);
    }

    /**
     * 注册一个新的PUT路由到路由器
     *
     * @param  string  $uri
     * @param  array|string|callable  $action
     * @return Route
     */
    public static function put($uri, $action)
    {
        $uri = self::fixRule($uri);
        return FacadesRoute::put($uri, $action);
    }

    /**
     * 注册一个新的DELETE路由到路由器
     *
     * @param  string  $uri
     * @param  array|string|callable  $action
     * @return Route
     */
    public static function delete($uri, $action)
    {
        $uri = self::fixRule($uri);
        return FacadesRoute::delete($uri, $action);
    }

    /**
     * 注册一个新的PATCH路由到路由器
     *
     * @param  string  $uri
     * @param  array|string|callable  $action
     * @return Route
     */
    public static function patch($uri, $action)
    {
        $uri = self::fixRule($uri);
        return FacadesRoute::patch($uri, $action);
    }

    /**
     * 注册一个新的OPTIONS路由到路由器
     *
     * @param  string  $uri
     * @param  array|string|callable  $action
     * @return Route
     */
    public static function options($uri, $action)
    {
        $uri = self::fixRule($uri);
        return FacadesRoute::options($uri, $action);
    }

    /**
     * 注册一个新的路由到路由器，该路由支持所有HTTP方法
     *
     * @param  string  $uri
     * @param  array|string|callable  $route
     * @return Route
     */
    public static function any($uri, $route)
    {
        $uri = self::fixRule($uri);
        return FacadesRoute::any($uri, $route);
    }

    /**
     * 路由资源到控制器
     *
     * @param  string  $name
     * @param  string  $controller
     * @param  array  $options
     * @return Resource
     */
    public static function resource($name, $controller, array $options = [])
    {
        $name = self::fixRule($name);
        return FacadesRoute::resource($name, $controller, $options);
    }

    /**
     * 创建一个路由组，该组共享相同的属性
     *
     * @param  string  $attributes
     * @param  \Closure|string  $routes
     * @return Group
     */
    public static function group(string $name, $routes, array $middleware = [])
    {
        $name = self::fixRule($name);
        return FacadesRoute::group(['prefix' => $name, 'middleware' => $middleware], $routes);
    }

    /**
     * 生成路由URL
     * 
     * @param string $name 路由名称
     * @param array $parameters 路由参数
     * @param bool $absolute 是否生成绝对URL
     * @return string
     */
    public static function url(string $name, array $parameters = [], bool $absolute = true): string
    {
        return route($name, $parameters, $absolute);
    }

    /**
     * @inheritDoc
     */
    public static function match($methods, $uri, $action)
    {
        $uri = self::fixRule($uri);
        return FacadesRoute::any($uri, $action, $methods);
    }

    /**
     * @inheritDoc
     */
    public static function fixRule(string $rule): string
    {
        $rule = preg_replace('/\[:([^\/]+)\]/', '{$1?}', $rule);
        $rule = preg_replace('/<([^\/]+)>/', '{$1}', $rule);
        $rule = preg_replace('/:([^\/]+)/', '{$1}', $rule);
        return $rule;
    }

    /**
     * 魔术方法，用于处理未实现的静态方法调用
     * 
     * @param string $name 方法名
     * @param array $arguments 方法参数
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([FacadesRoute::class, $name], $arguments);
    }
}
