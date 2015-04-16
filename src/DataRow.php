<?php
namespace Nayjest\Grids;

/**
 * Class DataRow
 *
 * Abstract class for DataRowInterface implementations
 *
 * @package Nayjest\Grids
 */
abstract class DataRow implements DataRowInterface
{

    /** @var  mixed row data */
    protected $src;

    /** @var int row id */
    protected $id;

    /**
     * Constructor.
     *
     * @param $src
     * @param int $id
     */
    public function __construct($src, $id)
    {
        $this->src = $src;
        $this->id = $id;
    }

    /**
     * Returns row id.
     *
     * It's row number starting from 1, considering pagination.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns row data source.
     *
     * @return mixed
     */
    public function getSrc()
    {
        return $this->src;
    }

    /**
     * Returns value for specified column.
     *
     * @param string $fieldName
     * @return mixed
     */
    abstract protected function extractCellValue($fieldName);

    /**
     * Returns value of specified column from row.
     *
     * @param FieldConfig|string $field
     * @return mixed
     */
    public function getCellValue($field)
    {
        $fieldName = $field instanceof FieldConfig ? $field->getName() : $field;
        return $this->extractCellValue($fieldName);
    }
}
