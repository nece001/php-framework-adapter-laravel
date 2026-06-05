<?php

namespace Nece\Framework\Adapter;

use Nece\Framework\Adapter\Contract\Controller as ContractController;
use Nece\Framework\Adapter\Request;
use Nece\Framework\Adapter\Facade\Response as FacadeResponse;
use Nece\Framework\Adapter\Facade\Session as FacadeSession;

class Controller implements ContractController
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var array
     */
    private $cookies = [];

    /**
     * 获取当前请求
     *
     * @return Request
     */
    public function request(): Request
    {
        if (!$this->request) {
            $this->request = new Request();
        }
        return $this->request;
    }

    /**
     * 渲染视图
     *
     * @param string $view 视图路径
     * @param array $data 视图数据
     * @return mixed
     */
    public function render(string $view, $data)
    {
        return view($view, $data);
    }

    /**
     * 返回响应
     *
     * @param string $body
     * @param integer $status
     * @param array $headers
     * @return mixed
     */
    public function response(string $body = '', int $status = 200, array $headers = [])
    {
        $response = response($body, $status, $headers);
        $this->applyCookies($response);
        return $response;
    }

    /**
     * 重定向
     *
     * @param string $url 重定向URL
     * @param int $code HTTP状态码
     * @return mixed
     */
    public function redirect(string $url, int $code = 302)
    {
        $response = redirect($url, $code);
        $this->applyCookies($response);
        return $response;
    }

    /**
     * 返回 JSON 响应
     *
     * @param mixed $data 数据
     * @param int $code HTTP状态码
     * @param array $headers 响应头
     * @return mixed
     */
    public function json($data, int $code = 200, array $headers = [])
    {
        $response = response()->json($data, $code, $headers, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $this->applyCookies($response);
        return $response;
    }

    /**
     * 返回 XML 响应
     *
     * @param mixed $data 数据
     * @param int $code HTTP状态码
     * @param array $headers 响应头
     * @return mixed
     */
    public function xml($data, int $code = 200, array $headers = [])
    {
        $headers = array_merge(['Content-Type' => 'text/xml; charset=utf-8'], $headers);
        if (is_array($data) || is_object($data)) {
            $xml = $this->arrayToXml($data);
        } else {
            $xml = (string)$data;
        }
        $response = response($xml, $code, $headers);
        $this->applyCookies($response);
        return $response;
    }

    /**
     * 获取 Session
     *
     * @param string $name Session键名
     * @param mixed $default 默认值
     * @return mixed
     */
    public function session(string $name = '', $default = null)
    {
        if (empty($name)) {
            return FacadeSession::all();
        }
        return FacadeSession::get($name, $default);
    }

    /**
     * 获取 Session 并删除
     *
     * @param string $name
     * @param mixed $default 默认值
     * @return mixed
     */
    public function pullSession(string $name, $default = null)
    {
        return FacadeSession::pull($name, $default);
    }

    /**
     * 设置 Session
     *
     * @param string $name Session键名
     * @param mixed $value Session值
     * @return $this
     */
    public function setSession(string $name, $value)
    {
        FacadeSession::set($name, $value);
        return $this;
    }

    /**
     * 删除 Session
     *
     * @param string $name Session键名
     * @return $this
     */
    public function deleteSession(string $name)
    {
        FacadeSession::delete($name);
        return $this;
    }

    /**
     * 设置 Session 过期时间
     *
     * @param int $life_time Session 过期时间（秒）
     * @return $this
     */
    public function setSessionLifeTime(int $life_time)
    {
        // Laravel的Session生命周期由配置文件管理，这里不做处理
        return $this;
    }

    /**
     * 文件下载
     *
     * @param string $file 文件路径
     * @param string|null $name 下载文件名
     * @param array $headers 响应头
     * @return mixed
     */
    public function download(string $file, string $name = null, array $headers = [])
    {
        $response = response()->download($file, $name, $headers);
        $this->applyCookies($response);
        return $response;
    }

    /**
     * 流式响应
     *
     * @param resource $stream 数据流
     * @param int $code HTTP状态码
     * @param array $headers 响应头
     * @return mixed
     */
    public function stream($stream, int $code = 200, array $headers = [])
    {
        $response = response()->stream(function () use ($stream) {
            fpassthru($stream);
        }, $code, $headers);
        $this->applyCookies($response);
        return $response;
    }

    /**
     * 设置 Cookie
     *
     * @param string $name Cookie名称
     * @param string $value Cookie值
     * @param int $expire 过期时间（秒）
     * @param string $path 路径
     * @param string $domain 域名
     * @param bool $secure 是否安全连接
     * @param bool $httpOnly 是否仅HTTP访问
     * @return $this
     */
    public function setCookie(string $name, string $value = '', int $expire = 0, string $path = '/', string $domain = '', bool $secure = false, bool $httpOnly = true)
    {
        $this->cookies[] = [
            'name' => $name,
            'value' => $value,
            'expire' => $expire,
            'path' => $path,
            'domain' => $domain,
            'secure' => $secure,
            'httpOnly' => $httpOnly,
        ];
        return $this;
    }

    /**
     * 删除 Cookie
     *
     * @param string $name Cookie名称
     * @param string $path 路径
     * @param string $domain 域名
     * @return $this
     */
    public function deleteCookie(string $name, string $path = '/', string $domain = '')
    {
        $this->cookies[] = [
            'name' => $name,
            'value' => '',
            'expire' => -1,
            'path' => $path,
            'domain' => $domain,
            'secure' => false,
            'httpOnly' => true,
        ];
        return $this;
    }

    /**
     * 返回成功分页数据
     *
     * @param Paginator $page
     * @return mixed
     */
    public function successPagedList(Paginator $page)
    {
        $data = [
            'code' => 0,
            'message' => 'success',
            'data' => $page->items(),
            'pagination' => [
                'total' => $page->total(),
                'per_page' => $page->perPage(),
                'current_page' => $page->currentPage(),
                'last_page' => $page->lastPage(),
            ],
        ];
        return $this->json($data);
    }

    /**
     * 返回成功数据
     *
     * @param mixed $data
     * @param string $message 消息内容
     * @return mixed
     */
    public function success($data = null, string $message = 'success')
    {
        return $this->json([
            'code' => 0,
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * 返回失败数据
     *
     * @param string $message
     * @param string $code
     * @param mixed $data
     * @return mixed
     */
    public function failure(string $message = 'failure', $code = '', $data = null)
    {
        return $this->json([
            'code' => $code ?: -1,
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * 返回异常数据
     *
     * @param \Exception $e
     * @return mixed
     */
    public function exception(\Exception $e)
    {
        return $this->json([
            'code' => $e->getCode() ?: -1,
            'message' => $e->getMessage(),
            'data' => null,
        ], 500);
    }

    /**
     * 应用Cookie到响应
     *
     * @param mixed $response
     */
    protected function applyCookies($response)
    {
        foreach ($this->cookies as $cookie) {
            $response->cookie(
                $cookie['name'],
                $cookie['value'],
                $cookie['expire'],
                $cookie['path'],
                $cookie['domain'],
                $cookie['secure'],
                $cookie['httpOnly']
            );
        }
        $this->cookies = [];
    }

    /**
     * 数组转XML
     *
     * @param mixed $data
     * @param string $root
     * @return string
     */
    protected function arrayToXml($data, $root = 'response')
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<' . $root . '>';
        $xml .= $this->arrayToXmlRecursive($data);
        $xml .= '</' . $root . '>';
        return $xml;
    }

    /**
     * 递归数组转XML
     *
     * @param mixed $data
     * @return string
     */
    protected function arrayToXmlRecursive($data)
    {
        $xml = '';
        foreach ($data as $key => $value) {
            $key = is_numeric($key) ? 'item' : $key;
            $xml .= '<' . $key . '>';
            if (is_array($value) || is_object($value)) {
                $xml .= $this->arrayToXmlRecursive((array)$value);
            } else {
                $xml .= htmlspecialchars((string)$value, ENT_XML1);
            }
            $xml .= '</' . $key . '>';
        }
        return $xml;
    }
}