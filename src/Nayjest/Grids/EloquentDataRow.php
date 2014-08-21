<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 27.05.14
 * Time: 16:18
 */

namespace Nayjest\Grids;


class EloquentDataRow extends DataRow
{

    protected function extractCellValue(FieldConfig $field)
    {
        $field_name = $field->getName();
        return  $this->src->{$field_name};
    }
} 