<?php

namespace Nece\Framework\Adapter\Facade;

use Illuminate\Support\Facades\Log as FacadesLog;
use Nece\Framework\Adapter\Contract\Facade\ILog;

class Log extends FacadesLog implements ILog
{
    /**
     * 紧急情况
     *
     * @author nece001@163.com
     * @create 2025-10-05 10:43:06
     *
     * @param string|\Stringable $message
     * @param array $context
     * @return void
     */
    static public function emergency(string|\Stringable $message, array $context = []): void
    {
        parent::emergency($message, $context);
    }

    /**
     * 警告
     *
     * @author nece001@163.com
     * @create 2025-10-05 10:43:22
     *
     * @param string|\Stringable $message
     * @param array $context
     * @return void
     */
    static public function alert(string|\Stringable $message, array $context = []): void
    {
        parent::alert($message, $context);
    }

    /**
     * 关键错误
     *
     * @author nece001@163.com
     * @create 2025-10-05 10:43:38
     *
     * @param string|\Stringable $message
     * @param array $context
     * @return void
     */
    static public function critical(string|\Stringable $message, array $context = []): void
    {
        parent::critical($message, $context);
    }

    /**
     * 错误
     *
     * @author nece001@163.com
     * @create 2025-10-05 10:43:54
     *
     * @param string|\Stringable $message
     * @param array $context
     * @return void
     */
    static public function error(string|\Stringable $message, array $context = []): void
    {
        parent::error($message, $context);
    }

    /**
     * 警告
     *
     * @author nece001@163.com
     * @create 2025-10-05 10:44:09
     *
     * @param string|\Stringable $message
     * @param array $context
     * @return void
     */
    static public function warning(string|\Stringable $message, array $context = []): void
    {
        parent::warning($message, $context);
    }

    /**
     * 通知
     *
     * @author nece001@163.com
     * @create 2025-10-05 10:44:24
     *
     * @param string|\Stringable $message
     * @param array $context
     * @return void
     */
    static public function notice(string|\Stringable $message, array $context = []): void
    {
        parent::notice($message, $context);
    }

    /**
     * 信息
     *
     * @author nece001@163.com
     * @create 2025-10-05 10:44:39
     *
     * @param string|\Stringable $message
     * @param array $context
     * @return void
     */
    static public function info(string|\Stringable $message, array $context = []): void
    {
        parent::info($message, $context);
    }

    /**
     * 调试
     *
     * @author nece001@163.com
     * @create 2025-10-05 10:44:54
     *
     * @param string|\Stringable $message
     * @param array $context
     * @return void
     */
    static public function debug(string|\Stringable $message, array $context = []): void
    {
        parent::debug($message, $context);
    }

    /**
     * 日志
     *
     * @author nece001@163.com
     * @create 2025-10-05 10:45:09
     *
     * @param mixed $level
     * @param string|\Stringable $message
     * @param array $context
     * @return void
     */
    static public function log($level, string|\Stringable $message, array $context = []): void
    {
        parent::log($level, $message, $context);
    }
}
