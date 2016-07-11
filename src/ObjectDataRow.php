<?php
namespace Nayjest\Grids;

use Exception;
use RuntimeException;

class ObjectDataRow extends DataRow
{
    /**
     * @param string $fieldName
     * @return mixed
     * @throws Exception
     */
    protected function extractCellValue($fieldName)
    {

        if (strpos($fieldName, '.') !== false) {
            $parts = explode('.', $fieldName);
            $res = $this->src;
            foreach ($parts as $part) {
                $res = data_get($res, $part);
                if ($res === null) {
                    return $res;
                }
            }
            return $res;
        } else {
            try {
                return $this->src->{$fieldName};
            } catch(Exception $e) {
                throw new RuntimeException(
                    "Can't read '$fieldName' property from DataRow",
                    0,
                    $e
                );
            }

        }
    }
}
