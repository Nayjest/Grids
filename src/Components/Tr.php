<?php
namespace Nayjest\Grids\Components;

use Nayjest\Grids\IDataRow;

class Tr extends HtmlTag
{
    protected $data_row;

    /**
     * @return IDataRow
     */
    public function getDataRow()
    {
        return $this->data_row;
    }

    /**
     * @param IDataRow $dataRow
     * @return $this
     */
    public function setDataRow(IDataRow $dataRow)
    {
        $this->data_row = $dataRow;
        return $this;
    }

    /**
     * Renders row cells
     *
     * @return string
     */
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

    /**
     * Returns tag content
     *
     * @return null|string
     */
    public function getContent()
    {
        return $this->getDataRow() ? $this->renderCells() : parent::getContent();
    }

}