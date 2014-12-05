<?php
namespace Nayjest\Grids\Components;

use Nayjest\Grids\Grid;

class ColumnHeadersRow extends HtmlTag {

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

    protected function createHeaders(Grid $grid)
    {
        foreach($grid->getConfig()->getColumns() as $column) {
            $this->addComponent(new ColumnHeader($column));
        }
    }
} 