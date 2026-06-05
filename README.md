# php-framework-adapter-laravel

laravel框架适配

# 安装

## 开启Session

修改文件：bootstrap\app.php

```php
->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(Illuminate\Session\Middleware\StartSession::class);
    })
```
