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

    public function render($row)
    {
        return $this->renderInternal(
            $this->extractRowData($row)
        );
    }

    protected function renderInternal($field) {
        return (string)$field;
    }

    protected function extractRowData($row) {
        if (isset($row[$this->name])) {
            return $row[$this->name];
        };
        throw new \Exception("Error rendering grid: Row does not contain data for '{$this->name}' column");
    }

} 