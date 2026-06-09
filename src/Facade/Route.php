<?php

namespace Nece\Framework\Adapter\Facade;

use Illuminate\Support\Facades\Route as LaravelRoute;
use Nece\Framework\Adapter\Contract\Facade\Route as RouteContract;

class Route implements RouteContract
{
    public static function addRules(array $rules): void
    {
        foreach ($rules as $rule) {
            $group = $rule['group'];
            $controllers = $rule['controllers'];

            $prefix = rtrim($group['prefix'], '/');

            foreach ($controllers as $controller) {
                $controller_class = $controller['controller'];
                $methods = $controller['methods'];
                foreach ($methods as $method) {
                    $path = $prefix . '/' . ltrim($method['path'], '/');
                    $action = $method['action'];
                    $name = $method['name'] ?? '';
                    $match = $method['match'] ?? false;
                    $mtd = $method['method'] ?? 'get';

                    $rounte = LaravelRoute::match($mtd, $path, [$controller_class, $action]);
                    if ($name) {
                        $rounte->name($name);
                    }
                    if ($match) {
                        $rounte->fallback();
                    }
                }
            }
        }
    }

    public static function url(string $name, array $params = []): string
    {
        $url = route($name, $params);

        // 把编码后的{}还原
        return str_replace(['%7B', '%7D'], ['{', '}'], $url);
    }
}
