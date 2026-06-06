# php-framework-adapter-laravel

laravel框架适配

# 安装

## 必要配置

修改文件：bootstrap\app.php

```php
->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(Illuminate\Session\Middleware\StartSession::class); // 开启Session
        $middleware->remove(ConvertEmptyStringsToNull::class); // 关掉这个中间件，这个中间件会自动将请求中的参数值为空字符串的参数值转换为 null
    })
```
