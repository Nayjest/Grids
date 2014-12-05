<?php

namespace Nayjest\Grids\Components;

use Nayjest\Grids\Components\Base\RenderableComponent;

class SortingControl extends RenderableComponent
{
    protected $template = '*.components.sorting_control';

    protected $column;

    protected $render_section = HtmlTag::SECTION_END;

    protected function getViewData()
    {
        return parent::getViewData() + [
            'column' => $this->column
        ];
    }

    public function __construct($column)
    {
        $this->column = $column;
    }

    /**
     * @return mixed
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @param mixed $column
     */
    public function setColumn($column)
    {
        $this->column = $column;
    }
} 