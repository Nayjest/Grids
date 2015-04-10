<?php
namespace Nayjest\Grids\Components;

use Nayjest\Grids\Grid;

/**
 * Class ColumnHeadersRow
 *
 * The component for rendering table row with column headers.
 *
 * @package Nayjest\Grids\Components
 */
class ColumnHeadersRow extends HtmlTag
{
    protected $tag_name = 'tr';

    /**
     * Initializes component with grid
     *
     * @param Grid $grid
     * @return null
     */
    public function initialize(Grid $grid)
    {
        $this->createHeaders($grid);
        parent::initialize($grid);
    }

    /**
     * Creates children components for rendering column headers.
     *
     * @param Grid $grid
     */
    protected function createHeaders(Grid $grid)
    {
        foreach ($grid->getConfig()->getColumns() as $column) {
            $this->addComponent(new ColumnHeader($column));
        }
    }
}
