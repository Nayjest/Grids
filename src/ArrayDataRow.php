<?php
namespace Nayjest\Grids;

class ArrayDataRow extends DataRow
{
    /**
     * {@inheritdoc}
     */
    protected function extractCellValue($fieldName)
    {
        if (strpos($fieldName, '.') !== false) {
            $parts = explode('.', $fieldName);
            $res = $this->src;
            foreach ($parts as $part) {
                if (isset($res[$part])) {
                    $res = $res[$part];
                } else {
                    return $res;
                }
            }
            return $res;
        } else {
            if (array_key_exists($fieldName, $this->src)) {
                return $this->src[$fieldName];
            } else {
                return null;
            }
        }
    }
}
