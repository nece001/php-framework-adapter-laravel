<?php

namespace Nece\Framework\Adapter;

use Nece\Framework\Adapter\Contract\Request as ContractRequest;
use Nece\Framework\Adapter\Facade\Session;
use think\Request as ThinkRequest;

class Request implements ContractRequest
{
    /**
     * 请求实例
     *
     * @var ThinkRequest|null
     */
    private $request;

    public function __construct()
    {
        $this->request = \request();
    }
}
