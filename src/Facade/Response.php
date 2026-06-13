<?php

namespace Nece\Framework\Adapter\Facade;

use Nece\Framework\Adapter\Contract\Facade\Response as ResponseContract;

class Response implements ResponseContract
{
    public static function response(string $body = '', int $status = 200, array $headers = [])
    {
        return response($body, $status, $headers);
    }

    public static function json($data, int $status = 200, array $headers = [], array $options = [])
    {
        $defaultOptions = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR;
        $mergedOptions = empty($options) ? $defaultOptions : $options;
        return response()->json($data, $status, $headers, $mergedOptions);
    }

    public static function xml($xml, int $status = 200, array $headers = [], array $options = [])
    {
        $defaultHeaders = ['Content-Type' => 'text/xml; charset=utf-8'];
        $mergedHeaders = array_merge($defaultHeaders, $headers);
        
        if (is_array($xml) || is_object($xml)) {
            $root = isset($options['root']) ? $options['root'] : 'response';
            $xml = static::arrayToXml($xml, $root);
        }
        return response($xml, $status, $mergedHeaders);
    }

    public static function jsonp($data, int $status = 200, array $headers = [], array $options = [])
    {
        $callbackName = isset($options['callback']) ? $options['callback'] : 'callback';
        return response()->jsonp($callbackName, $data, $status, $headers);
    }

    public static function redirect(string $location, int $status = 302)
    {
        return redirect($location, $status);
    }

    public static function view(mixed $template = null, array $vars = [], int $status = 200)
    {
        return response()->view($template, $vars, $status);
    }

    public static function download(string $filename, string $name = '', bool $content = false, int $expire = 180)
    {
        if ($content) {
            $response = response($filename, 200, [
                'Content-Type' => 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename="' . ($name ?: 'download') . '"',
                'Cache-Control' => 'max-age=' . $expire,
            ]);
            return $response;
        }
        return response()->download($filename, $name ?: null);
    }

    public static function notFound()
    {
        abort(404);
    }

    public static function buildData($code, $status, $message, $data = [])
    {
        return [
            'code' => $code,
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ];
    }

    protected static function arrayToXml($data, $root = 'response')
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<' . $root . '>';
        $xml .= static::arrayToXmlRecursive($data);
        $xml .= '</' . $root . '>';
        return $xml;
    }

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