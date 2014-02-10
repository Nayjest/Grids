<?php
namespace Nayjest\Grids;


class Column implements ColumnInterface
{

    protected $label;

    protected $name;

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel($text)
    {
        $this->label = $text;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function renderHeader()
    {
        return (string)$this->getLabel();
    }

    public function render($field)
    {
        return (string)$field;
    }

} 