<?php
namespace Nayjest\Grids;


interface IDataRow
{
    public function getId();

    /**
     * @param string|FieldConfig $field
     * @return mixed
     */
    public function getCellValue($field);
} 