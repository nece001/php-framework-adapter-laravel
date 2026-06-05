<?php

namespace Nece\Framework\Adapter\Facade;

use Nece\Framework\Adapter\Contract\Facade\Response as ResponseContract;

class Response implements ResponseContract
{
    /**
     * 基础响应
     *
     * @param string $body 响应体
     * @param int $status 状态码
     * @param array $headers 请求头
     * @return mixed
     */
    public static function response(string $body = '', int $status = 200, array $headers = [])
    {
        return response($body, $status, $headers);
    }

    /**
     * JSON 响应
     *
     * @param mixed $data 数据
     * @param int $options 选项
     * @return mixed
     */
    public static function json($data, int $options = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)
    {
        return response()->json($data, 200, [], $options);
    }

    /**
     * XML 响应
     *
     * @param mixed $xml XML数据
     * @return mixed
     */
    public static function xml($xml)
    {
        $headers = ['Content-Type' => 'text/xml; charset=utf-8'];
        if (is_array($xml) || is_object($xml)) {
            $xml = static::arrayToXml($xml);
        }
        return response($xml, 200, $headers);
    }

    /**
     * JSONP 响应
     *
     * @param mixed $data 数据
     * @param string $callback_name 回调函数名
     * @return mixed
     */
    public static function jsonp($data, string $callback_name = 'callback')
    {
        return response()->jsonp($callback_name, $data);
    }

    /**
     * 重定向响应
     *
     * @param string $location 重定向地址
     * @param int $status 状态码
     * @param array $headers 请求头
     * @return mixed
     */
    public static function redirect(string $location, int $status = 302, array $headers = [])
    {
        return redirect($location, $status, $headers);
    }

    /**
     * 视图响应
     *
     * @param mixed $template 模板
     * @param array $vars 变量
     * @param string|null $app 应用
     * @param string|null $plugin 插件
     * @return mixed
     */
    public static function view(mixed $template = null, array $vars = [], ?string $app = null, ?string $plugin = null)
    {
        return view($template, $vars);
    }

    /**
     * 文件下载响应
     *
     * @param string $file_path 文件路径
     * @param string|null $filename 文件名
     * @return mixed
     */
    public static function download(string $file_path, ?string $filename = null)
    {
        return response()->download($file_path, $filename);
    }

    /**
     * 404 未找到
     *
     * @return mixed
     */
    public static function notFound()
    {
        abort(404);
    }

    /**
     * 数组转XML
     *
     * @param mixed $data
     * @param string $root
     * @return string
     */
    protected static function arrayToXml($data, $root = 'response')
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<' . $root . '>';
        $xml .= static::arrayToXmlRecursive($data);
        $xml .= '</' . $root . '>';
        return $xml;
    }

    /**
     * 递归数组转XML
     *
     * @param mixed $data
     * @return string
     */
    protected static function arrayToXmlRecursive($data)
    {
        $xml = '';
        foreach ($data as $key => $value) {
            $key = is_numeric($key) ? 'item' : $key;
            $xml .= '<' . $key . '>';
            if (is_array($value) || is_object($value)) {
                $xml .= static::arrayToXmlRecursive((array)$value);
            } else {
                $xml .= htmlspecialchars((string)$value, ENT_XML1);
            }
            $xml .= '</' . $key . '>';
        }
        return $xml;
    }
}