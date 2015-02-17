<?php

namespace Nayjest\Grids\Components;

use Nayjest\Grids\FieldConfig;

class TableCell extends HtmlTag
{
    protected $tag_name = 'td';

    /** @var  FieldConfig */
    protected $column;

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

    public function getName()
    {
        return $this->name ? : 'column_' . $this->column->getName();
    }

    /**
     * @return mixed
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