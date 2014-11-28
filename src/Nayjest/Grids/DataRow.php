<?php
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

    public function getSrc()
    {
        return $this->src;
    }

    abstract protected function extractCellValue($fieldName);

    /**
     * @param FieldConfig|string $field
     * @return mixed
     */
    public function getCellValue($field)
    {
        $fieldName = $field instanceof FieldConfig ? $field->getName() : $field;
        return $this->extractCellValue($fieldName);
    }
} 