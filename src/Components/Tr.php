<?php
namespace Nayjest\Grids\Components;

use Nayjest\Grids\DataRowInterface;

/**
 * Class Tr
 *
 * The component for rendering TR html tag inside grid.
 *
 * @package Nayjest\Grids\Components
 */
class Tr extends HtmlTag
{
    /** @var DataRowInterface  */
    protected $data_row;

    /**
     * Returns data row.
     *
     * @return DataRowInterface
     */
    public function getDataRow()
    {
        return $this->data_row;
    }

    /**
     * Allows to set data row.
     *
     * @param DataRowInterface $dataRow
     * @return $this
     */
    public function setDataRow(DataRowInterface $dataRow)
    {
        $this->data_row = $dataRow;
        return $this;
    }

    /**
     * Renders row cells.
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
     * Returns tag content.
     *
     * @return null|string
     */
    public function getContent()
    {
        return $this->getDataRow() ? $this->renderCells() : parent::getContent();
    }
}
