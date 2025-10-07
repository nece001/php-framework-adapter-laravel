<?php

namespace Nece\Framework\Adapter;

use App\Http\Controllers\Controller as BaseController;
use Nece\Framework\Adapter\Contract\IController;
use Nece\Framework\Adapter\Response as AdapterResponse;
use Nece\Gears\PagingVar;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * 控制器基类
 *
 * @author nece001@163.com
 * @create 2025-10-05 13:48:11
 * 
 * @implements Request<Request>
 * @implements Response<Response>
 */
abstract class Controller extends BaseController implements IController
{
    /**
     * 分页参数名
     *
     * @var string
     */
    protected $page_var_name = 'page';

    /**
     * 每页项目数量参数名
     *
     * @var string
     */
    protected $page_size_var_name = 'page_size';

    /**
     * 获取当前请求
     * 
     * @return Request
     */
    public function getRequest()
    {
        return request();
    }

    /**
     * 获取分页参数
     *
     * @author nece001@163.com
     * @create 2025-10-07 10:20:55
     *
     * @return PagingVar
     */
    public function getPagingVar()
    {
        $page = $this->getRequest()->param($this->page_var_name, 1);
        $page_size = $this->getRequest()->param($this->page_size_var_name, 20);

        return new PagingVar($page, $page_size, $this->page_var_name, $this->page_size_var_name);
    }

    /**
     * 获取请求参数
     *
     * @author nece001@163.com
     * @create 2025-10-05 12:50:26
     *
     * @param string $name 参数键名
     * @param mixed $default 默认值
     * @param string|array|null $filter 过滤函数
     * @return mixed
     */
    public function param($name = null, $default = null, string|array|null $filter = null)
    {
        return $this->getRequest()->input($name, $default, $filter);
    }

    /**
     * 渲染视图
     * 
     * @param string $template 模板路径
     * @param array $data 视图数据
     * @return Response
     */
    public function renderView(string $template, $data)
    {
        $response = new AdapterResponse($this->getRequest()->is_json_request);
        return $response->view($template, $data);
    }

    /**
     * 重定向
     * 
     * @param string $url 重定向URL
     * @param mixed $result 重定向结果
     * @param int $code HTTP状态码
     * @return Response
     */
    public function redirectTo(string $url, $result, int $code = 302)
    {
        $response = new AdapterResponse($this->getRequest()->is_json_request);
        return $response->redirect($url, $result, $code);
    }
}
