<?php
namespace Nayjest\Grids;


interface IDataRow
{
    public function getId();

    public function getCellValue(FieldConfig $field);
} 