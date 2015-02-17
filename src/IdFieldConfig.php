<?php
namespace Nayjest\Grids;

class IdFieldConfig extends FieldConfig
{

    public function __construct()
    {
        $this->setName('ID');
    }

    public function getValue(DataRow $row)
    {
        return $row->getId();
    }
}