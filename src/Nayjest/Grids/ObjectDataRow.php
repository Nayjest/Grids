<?php
namespace Nayjest\Grids;

use \Exception;

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
                $res = $res->{$part};
                if ($res === null) {
                    return $res;
                }
            }
            return $res;
        } else {
            try {
                return $this->src->{$fieldName};
            } catch(Exception $e) {
                var_dump($this->src);
                echo "!!!";
                die();
                //throw new Exception("Can't read '$fieldName' property from DataRow");
            }

        }
    }
} 