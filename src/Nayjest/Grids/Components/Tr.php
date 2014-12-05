<?php
namespace Nayjest\Grids\Components;

use Nayjest\Grids\DataRow;

class Tr extends HtmlTag
{
    protected $data_row;

    /**
     * @return mixed
     */
    public function getDataRow()
    {
        return $this->data_row;
    }

    /**
     * @param DataRow $data_row
     * @return $this
     */
    public function setDataRow($data_row)
    {
        $this->data_row = $data_row;
        return $this;
    }

    protected function renderCells()
    {
        $row = $this->getDataRow();
        $out = '';
        foreach($this->grid->getConfig()->getColumns() as $column) {
            $component = new TableCell($column);
            $component->initialize($this->grid);
            $component->setContent($column->getValue($row));
            $out .= $component->render();
        }
        return $out;
    }

    public function getContent()
    {
        return $this->getDataRow() ? $this->renderCells() : parent::getContent();
    }

}