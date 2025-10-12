<?php

namespace Nece\Framework\Adapter;

use Illuminate\Console\Command as ConsoleCommand;
use Nece\Framework\Adapter\Contract\ICommand;

abstract class Command extends ConsoleCommand implements ICommand {}
