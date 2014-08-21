<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 27.05.14
 * Time: 16:18
 */

namespace Nayjest\Grids;


abstract class DataRow
{

    protected $src;

    protected $id;

    public function __construct($src, $id)
    {
        $this->src = $src;
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    abstract protected function extractCellValue(FieldConfig $field);

    public function getCellValue(FieldConfig $field)
    {
        $value =  $this->extractCellValue($field);
        if ($f = $field->getCallback()) {
            return call_user_func($f, $value);
        } else {
            return $value;
        }
    }
} 