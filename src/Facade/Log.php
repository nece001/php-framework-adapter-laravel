<?php

namespace Nece\Framework\Adapter\Facade;

use Illuminate\Support\Facades\Log as FacadesLog;
use Nece\Framework\Adapter\Contract\Facade\Log as ContractFacadeLog;

class Log extends FacadesLog implements ContractFacadeLog {}
