<?php

namespace Nece\Framework\Adapter;

use Illuminate\Console\Command as ConsoleCommand;
use Nece\Framework\Adapter\Contract\ICommand;

abstract class Command extends ConsoleCommand implements ICommand
{
    protected function write(string $message)
    {
        return $this->output->write($message);
    }

    protected function writeln(string $message)
    {
        return $this->output->writeln($message);
    }
}
