<?php

namespace Nece\Framework\Adapter\Database\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

trait SoftDelete
{
    use SoftDeletes;

    /**
     * 加表别名
     *
     * @author nece001@163.com
     * @create 2025-10-08 11:33:47
     *
     * @return string
     */
    public function getQualifiedDeletedAtColumn()
    {
        $field = $this->getDeletedAtColumn();
        if ($this->alias) {
            $field = $this->alias . '.' . $field;
        }

        return $this->qualifyColumn($field);
    }
}
