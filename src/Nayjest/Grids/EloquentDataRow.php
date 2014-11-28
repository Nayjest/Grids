<?php
namespace Nayjest\Grids;


class EloquentDataRow extends DataRow
{

    /**
     * @param string $fieldName
     * @return mixed
     */
    protected function extractCellValue($fieldName)
    {
        if (strpos($fieldName, '.') !== false) {
            $parts = explode('.', $fieldName);
            $res = $this->src;
            foreach ($parts as $part) {
                $res = $res->{$part};
                if ($res === null) {
                    return $res;
                }
            }
            return $res;
        } else {
            return $this->src->{$fieldName};
        }
    }
} 