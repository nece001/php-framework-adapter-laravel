<?php

namespace Nece\Framework\Adapter;

use Nece\Framework\Adapter\Contract\Controller as ContractController;
use Nece\Framework\Adapter\Request;
use Nece\Framework\Adapter\Facade\Response;
use Nece\Framework\Adapter\Facade\Session as FacadeSession;

class Controller implements ContractController
{
    private  $request;

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
}
