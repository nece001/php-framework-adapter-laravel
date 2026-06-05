<?php

namespace Nece\Framework\Adapter\Facade;

use Illuminate\Support\Facades\Config as FacadesConfig;
use Nece\Framework\Adapter\Contract\Facade\Config as FacadeConfig;

class Config extends FacadesConfig implements FacadeConfig {}