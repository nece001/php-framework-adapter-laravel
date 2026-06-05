<?php

namespace Nece\Framework\Adapter\Facade;

use Illuminate\Support\Facades\Log as LaravelLog;
use Nece\Framework\Adapter\Contract\Facade\Log as ContractFacadeLog;
use Psr\Log\LoggerInterface;

class Log implements ContractFacadeLog
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

    /**
     * 紧急情况
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function emergency(string $message, array $context = []): void
    {
        LaravelLog::emergency($message, $context);
    }

    /**
     * 警告
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function alert(string $message, array $context = []): void
    {
        LaravelLog::alert($message, $context);
    }

    /**
     * 关键错误
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function critical(string $message, array $context = []): void
    {
        LaravelLog::critical($message, $context);
    }

    /**
     * 错误
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function error(string $message, array $context = []): void
    {
        LaravelLog::error($message, $context);
    }

    /**
     * 警告
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function warning(string $message, array $context = []): void
    {
        LaravelLog::warning($message, $context);
    }

    /**
     * 通知
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function notice(string $message, array $context = []): void
    {
        LaravelLog::notice($message, $context);
    }

    /**
     * 信息
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function info(string $message, array $context = []): void
    {
        LaravelLog::info($message, $context);
    }

    /**
     * 调试
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function debug(string $message, array $context = []): void
    {
        LaravelLog::debug($message, $context);
    }

    /**
     * 日志
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function log($level, string $message, array $context = []): void
    {
        LaravelLog::log($level, $message, $context);
    }
}