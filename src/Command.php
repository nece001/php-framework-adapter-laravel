<?php

namespace Nece\Framework\Adapter;

use Nece\Framework\Adapter\Contract\Command as ContractCommand;
use think\console\Command as ConsoleCommand;

abstract class Command extends ConsoleCommand implements ContractCommand
{
    /**
     * 默认命令名称
     *
     * @var string
     */
    protected static $defaultName = '';

    /**
     * 默认命令描述
     *
     * @var string
     */
    protected static $defaultDescription = '';

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;
}
