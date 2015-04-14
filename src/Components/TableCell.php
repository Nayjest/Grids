<?php

namespace Nayjest\Grids\Components;

use Nayjest\Grids\FieldConfig;

/**
 * Class TableCell
 * 
 * The component for rendering TD html tag inside grid.
 *
 * @package Nayjest\Grids\Components
 */
class TableCell extends HtmlTag
{
    protected $tag_name = 'td';

    /** @var  FieldConfig */
    protected $column;

    /**
     * Constructor.
     *
     * @param FieldConfig $column
     */
    public function __construct(FieldConfig $column) {

        $this->setColumn($column);
    }

    public function getAttributes()
    {
        if (empty($this->attributes['class'])) {
            $this->attributes['class'] = 'column-' . $this->getColumn()->getName();
        }
        if ($this->column->isHidden()) {
            $this->attributes['style'] = 'display:none;';
        }
        return $this->attributes;
    }

    /**
     * Returns component name.
     * By default it's column_{$column_name}
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name ? : 'column_' . $this->column->getName();
    }

    /**
     * Returns associated column.
     *
     * @return FieldConfig $column
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @param FieldConfig $column
     * @return $this
     */
    public function setColumn(FieldConfig $column)
    {
        $this->column = $column;
        return $this;
    }
}

