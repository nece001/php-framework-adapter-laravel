<?php

namespace Nece\Framework\Adapter\Database\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

trait SoftDelete
{
    use SoftDeletes;
}