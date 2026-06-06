<?php

namespace Nece\Framework\Adapter;

use Nece\Framework\Adapter\Contract\Request as ContractRequest;
use Nece\Framework\Adapter\Facade\Session;
use Nece\Framework\Adapter\UploadFile;
use Illuminate\Http\Request as IlluminateRequest;

class Request implements ContractRequest
{
    /**
     * 请求实例
     *
     * @var IlluminateRequest|null
     */
    private $request;

    public function __construct()
    {
        $this->request = \request();
    }

    /**
     * 获取当前请求的参数（合并GET、POST、路由参数）
     *
     * @param string|array $name    变量名，支持数组批量获取
     * @param mixed        $default 默认值
     * @param string|array $filter  过滤方法
     * @return mixed
     */
    public function param($name = '', $default = null, $filter = '')
    {
        return $this->getValue($this->request->input(), $name, $default, $filter);
    }

    /**
     * 获取包含文件在内的所有请求参数
     *
     * @param string|array $name   变量名
     * @param string|array $filter 过滤方法
     * @return mixed
     */
    public function all($name = '', $filter = '')
    {
        return $this->getValue($this->request->all(), $name, null, $filter);
    }

    /**
     * 获取GET参数
     *
     * @param string|array|bool $name    变量名，true返回原始数组
     * @param mixed             $default 默认值
     * @param string|array      $filter  过滤方法
     * @return mixed
     */
    public function get($name = '', $default = null, $filter = '')
    {
        return $this->getValue($this->request->query(), $name, $default, $filter);
    }

    /**
     * 获取POST参数
     *
     * @param string|array|bool $name    变量名，true返回原始数组
     * @param mixed             $default 默认值
     * @param string|array      $filter  过滤方法
     * @return mixed
     */
    public function post($name = '', $default = null, $filter = '')
    {
        return $this->getValue($this->request->post(), $name, $default, $filter);
    }

    /**
     * 获取PUT参数
     *
     * @param string|array|bool $name    变量名，true返回原始数组
     * @param mixed             $default 默认值
     * @param string|array      $filter  过滤方法
     * @return mixed
     */
    public function put($name = '', $default = null, $filter = '')
    {
        return $this->getValue($this->request->input(), $name, $default, $filter);
    }

    /**
     * 获取DELETE参数
     *
     * @param string|array|bool $name    变量名，true返回原始数组
     * @param mixed             $default 默认值
     * @param string|array      $filter  过滤方法
     * @return mixed
     */
    public function delete($name = '', $default = null, $filter = '')
    {
        return $this->put($name, $default, $filter);
    }

    /**
     * 获取变量（底层方法，支持过滤和默认值）
     *
     * @param string|bool  $name    字段名，false返回原始数据
     * @param mixed        $default 默认值
     * @param string|array $filter  过滤函数
     * @return mixed
     */
    public function input($name = '', $default = null, $filter = '')
    {
        return $this->getValue($this->request->input(), $name, $default, $filter);
    }

    /**
     * 获取路由参数
     *
     * @param string|array|bool $name    变量名，true返回原始数组
     * @param mixed             $default 默认值
     * @param string|array      $filter  过滤方法
     * @return mixed
     */
    public function route($name = '', $default = null, $filter = '')
    {
        return $this->getValue($this->request->route()?->parameters() ?? [], $name, $default, $filter);
    }

    /**
     * 统一获取值的方法
     *
     * @param array             $data    数据源
     * @param string|array|bool $name    变量名
     * @param mixed             $default 默认值
     * @param string|array      $filter  过滤方法
     * @return mixed
     */
    protected function getValue(array $data, $name, $default = null, $filter = '')
    {
        if ($name === true) {
            return $data;
        }
        if (is_array($name)) {
            $result = [];
            foreach ($name as $key) {
                $result[$key] = $this->applyFilter($data[$key] ?? $default, $filter);
            }
            return $result;
        }
        if (empty($name)) {
            foreach ($data as &$value) {
                $value = $this->applyFilter($value, $filter);
            }
            return $data;
        }
        return $this->applyFilter($data[$name] ?? $default, $filter);
    }

    /**
     * 获取Cookie参数
     *
     * @param string       $name    变量名
     * @param mixed        $default 默认值
     * @param string|array $filter  过滤方法
     * @return mixed
     */
    public function cookie(string $name = '', $default = null, $filter = '')
    {
        if (empty($name)) {
            return $this->request->cookies->all();
        }
        return $this->applyFilter($this->request->cookie($name, $default), $filter);
    }

    /**
     * 获取Session数据
     *
     * @param string $name    变量名，空字符串返回所有session
     * @param mixed  $default 默认值
     * @return mixed
     */
    public function session(string $name = '', $default = null)
    {
        if (empty($name)) {
            return Session::all();
        }
        return Session::get($name, $default);
    }

    /**
     * 获取SERVER参数
     *
     * @param string $name    变量名（不区分大小写）
     * @param string $default 默认值
     * @return mixed
     */
    public function server(string $name = '', string $default = '')
    {
        if (empty($name)) {
            return $_SERVER;
        }
        $name = strtoupper($name);
        return $_SERVER[$name] ?? $default;
    }

    /**
     * 获取Header信息
     *
     * @param string     $name    header名称
     * @param string     $default 默认值
     * @return array|string
     */
    public function header(string $name = '', string $default = null)
    {
        if (empty($name)) {
            return $this->request->headers->all();
        }
        return $this->request->header($name, $default);
    }

    /**
     * 获取上传文件
     *
     * @param string $name 文件字段名
     * @return array
     */
    public function file(string $name = '')
    {
        if (empty($name)) {
            $files = $this->request->allFiles();
            return UploadFile::instances($files);
        }
        $file = $this->request->file($name);
        if (!$file) {
            return [];
        }
        if (is_array($file)) {
            return UploadFile::instances($file);
        }
        return [UploadFile::instance($file)];
    }

    /**
     * 判断请求类型
     *
     * @param bool $origin 是否获取原始请求类型
     * @return string
     */
    public function method(bool $origin = false): string
    {
        if ($origin) {
            return $this->request->getRealMethod();
        }
        return $this->request->method();
    }

    /**
     * 是否为GET请求
     * @return bool
     */
    public function isGet(): bool
    {
        return $this->request->isMethod('get');
    }

    /**
     * 是否为POST请求
     * @return bool
     */
    public function isPost(): bool
    {
        return $this->request->isMethod('post');
    }

    /**
     * 是否为PUT请求
     * @return bool
     */
    public function isPut(): bool
    {
        return $this->request->isMethod('put');
    }

    /**
     * 是否为DELETE请求
     * @return bool
     */
    public function isDelete(): bool
    {
        return $this->request->isMethod('delete');
    }

    /**
     * 是否为AJAX请求
     *
     * @param bool $ajax true时只检测X-Requested-With头
     * @return bool
     */
    public function isAjax(bool $ajax = false): bool
    {
        return $this->request->ajax() || ($ajax && $this->request->wantsJson());
    }

    /**
     * 是否为JSON请求
     * @return bool
     */
    public function isJson(): bool
    {
        return $this->request->wantsJson();
    }

    /**
     * 是否为HTTPS请求
     * @return bool
     */
    public function isSsl(): bool
    {
        return $this->request->isSecure();
    }

    /**
     * 是否为CLI模式
     * @return bool
     */
    public function isCli(): bool
    {
        return php_sapi_name() === 'cli';
    }

    /**
     * 是否存在指定请求参数
     *
     * @param string $name       参数名
     * @param string $type       参数类型
     * @param bool   $checkEmpty 是否检查空值
     * @return bool
     */
    public function has(string $name, string $type = 'param', bool $checkEmpty = false): bool
    {
        switch ($type) {
            case 'get':
                $value = $this->get($name);
                break;
            case 'post':
                $value = $this->post($name);
                break;
            case 'put':
                $value = $this->put($name);
                break;
            case 'route':
                $value = $this->route($name);
                break;
            case 'cookie':
                $value = $this->cookie($name);
                break;
            case 'session':
                $value = $this->session($name);
                break;
            default:
                $value = $this->param($name);
        }
        if ($checkEmpty) {
            return !empty($value);
        }
        return $value !== null;
    }

    /**
     * 只获取指定的参数
     *
     * @param array        $name   要获取的参数名数组
     * @param string|array $data   数据源类型或数组
     * @param string|array $filter 过滤方法
     * @return array
     */
    public function only(array $name, $data = 'param', $filter = ''): array
    {
        if (is_array($data)) {
            $source = $data;
        } else {
            switch ($data) {
                case 'get':
                    $source = $this->get(true);
                    break;
                case 'post':
                    $source = $this->post(true);
                    break;
                case 'put':
                    $source = $this->put(true);
                    break;
                case 'route':
                    $source = $this->route(true);
                    break;
                default:
                    $source = $this->param(true);
            }
        }
        $result = [];
        foreach ($name as $key) {
            $result[$key] = $this->applyFilter($source[$key] ?? null, $filter);
        }
        return $result;
    }

    /**
     * 排除指定参数后获取
     *
     * @param array  $name 要排除的参数名数组
     * @param string $type 参数类型
     * @return array
     */
    public function except(array $name, string $type = 'param'): array
    {
        switch ($type) {
            case 'get':
                $source = $this->get(true);
                break;
            case 'post':
                $source = $this->post(true);
                break;
            case 'put':
                $source = $this->put(true);
                break;
            case 'route':
                $source = $this->route(true);
                break;
            default:
                $source = $this->param(true);
        }
        return array_diff_key($source, array_flip($name));
    }

    /**
     * 获取客户端IP地址
     * @return string
     */
    public function ip(): string
    {
        return $this->request->ip();
    }

    /**
     * 获取当前URL
     *
     * @param bool $complete 是否包含完整域名
     * @return string
     */
    public function url(bool $complete = false): string
    {
        if ($complete) {
            return $this->request->fullUrl();
        }
        return $this->request->url();
    }

    /**
     * 获取当前域名
     *
     * @param bool $port 是否包含端口号
     * @return string
     */
    public function domain(bool $port = false): string
    {
        $domain = $this->request->getHost();
        if ($port) {
            $domain .= ':' . $this->request->getPort();
        }
        return $domain;
    }

    /**
     * 获取当前请求的pathinfo
     * @return string
     */
    public function pathinfo(): string
    {
        return $this->request->path();
    }

    /**
     * 获取当前请求的path
     * @return string
     */
    public function path(): string
    {
        return '/' . $this->request->path();
    }

    /**
     * 获取当前URL的后缀
     * @return string
     */
    public function ext(): string
    {
        return $this->request->route()?->parameter('extension') ?? '';
    }

    /**
     * 获取当前请求的Content-Type
     * @return string
     */
    public function contentType(): string
    {
        return $this->request->contentType() ?? '';
    }

    /**
     * 获取当前请求的完整内容
     * @return string
     */
    public function getContent(): string
    {
        return $this->request->getContent();
    }

    /**
     * 获取请求时间
     *
     * @param bool $float 是否返回浮点类型
     * @return int
     */
    public function time(bool $float = false): int
    {
        if ($float) {
            return (int)($this->request->server('REQUEST_TIME_FLOAT') * 1000000);
        }
        return $this->request->server('REQUEST_TIME');
    }

    /**
     * 应用过滤器
     *
     * @param mixed        $value
     * @param string|array $filter
     * @return mixed
     */
    protected function applyFilter($value, $filter)
    {
        if (empty($filter) || $value === null) {
            return $value;
        }

        $filters = is_array($filter) ? $filter : explode(',', $filter);

        foreach ($filters as $f) {
            $f = trim($f);
            if (function_exists($f)) {
                $value = $f($value);
            } elseif (is_callable($f)) {
                $value = $f($value);
            }
        }

        return $value;
    }

    public function __get($name)
    {
        return $this->request->$name;
    }
}