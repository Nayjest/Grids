<?php
namespace Nayjest\Grids;


class EloquentDataRow extends DataRow
{

    protected function extractCellValue($field_name)
    {
        if (strpos($field_name, '.') !== false) {
            $parts = explode('.', $field_name);
            $res = $this->src;
            foreach ($parts as $part) {
                $res = $res->{$part};
                if ($res === null) {
                    return $res;
                }
            }
            return $res;
        } else {
            return $this->src->{$field_name};
        }
    }
} 