<?php
namespace Nayjest\Grids;

/**
 * Interface IDataRow
 *
 * Interface for row of data received from data provider
 *
 * @package Nayjest\Grids
 */
interface IDataRow
{
    public function getId();

    /**
     * @param string|FieldConfig $field
     * @return mixed
     */
    public function getCellValue($field);
} 