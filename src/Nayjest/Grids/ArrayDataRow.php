<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 27.05.14
 * Time: 16:18
 */

namespace Nayjest\Grids;


class ArrayDataRow extends DataRow
{

    protected function extractCellValue($field_name)
    {
        return $this->src[$field_name];
    }
} 