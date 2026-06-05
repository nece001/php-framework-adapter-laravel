<?php

namespace Nece\Framework\Adapter\Facade;

use Illuminate\Support\Facades\Log as FacadesLog;
use Nece\Framework\Adapter\Contract\Facade\Log as ContractFacadeLog;
use Psr\Log\LoggerInterface;

class Log extends FacadesLog implements ContractFacadeLog
{
    /**
     * 获取日志记录器
     *
     * @return LoggerInterface
     */
    public static function getLogger(): LoggerInterface
    {
        return app('log');
    }
}