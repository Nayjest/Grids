<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 27.05.14
 * Time: 16:18
 */

namespace Nayjest\Grids;


abstract class DataRow implements IDataRow
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
        return $this->extractCellValue($field);
    }
} 