<?php

namespace Nece\Framework\Adapter;

use Nece\Framework\Adapter\Facade\Session;
use Nece\Gears\ResponseData;
use Throwable;

class Response
{
    private $is_json;

    public function __construct(bool $is_json)
    {
        $this->is_json = $is_json;
    }

    /**
     * 渲染视图
     * 
     * @param string $template 模板路径
     * @param array $data 视图数据
     * @return Response
     */
    public function view(string $template, $data)
    {
        if ($this->is_json) {
            $data = ResponseData::success($data);
            return json($data, $data['http_status']);
        } else {
            return view($template, $data);
        }
    }

    /**
     * 重定向
     * 
     * @param string $url 重定向URL
     * @param mixed $result 重定向结果
     * @param int $code HTTP状态码
     * @return Response
     */
    public function redirect(string $url, $result, int $code = 302)
    {
        if ($this->is_json) {
            if ($result instanceof Throwable) {
                $data = ResponseData::exception($result);
            } else {
                $data = ResponseData::success($result);
            }
            return json($data, $data['http_status']);
        } else {
            if ($result instanceof Throwable) {
                Session::set('error_message', $result->getMessage());
            } else {
                Session::set('redirect_data', $result);
            }
            return redirect($url, $code);
        }
    }
}
