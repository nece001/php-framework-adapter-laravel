<?php

namespace Nece\Framework\Adapter\DbAdapter;

use Nece\Framework\Adapter\Contract\DbAdapter\ModelRelationQuery as DbAdapterModelRelationQuery;
use think\db\Query as ThinkQuery;

class ModelRelationQuery extends Query implements DbAdapterModelRelationQuery {}
