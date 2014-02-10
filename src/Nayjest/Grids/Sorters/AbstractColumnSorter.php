<?php
namespace Nayjest\Grids\Sorters;


abstract class AbstractColumnSorter extends AbstractSorter
{
    /**
     * @var string
     */
    protected $columnName;

    public function setColumnName($columnName)
    {
        $this->columnName = $columnName;
    }

    public function getColumnName()
    {
        return $this->columnName;
    }
} 