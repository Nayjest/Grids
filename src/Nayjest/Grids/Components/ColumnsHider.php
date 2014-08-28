<?php
namespace Nayjest\Grids\Components;

use Cookie;
use Nayjest\Grids\Components\Base\RenderableComponent;

class ColumnsHider extends RenderableComponent
{

    protected $template = '*.components.columns_hider';

    protected $name = 'columns_hider';

    protected $hidden_by_default = [];

    public function setHiddenByDefault($column_names)
    {
        $this->hidden_by_default = $column_names;
        return $this;
    }

    public function getHiddenByDefault()
    {
        return $this->hidden_by_default;
    }

    public function getColumnsVisibility()
    {
        $key = $this->getId('cookie');
        if (isset($_COOKIE[$key])) {
            $from_cookie = json_decode($_COOKIE[$key], true);
        } else {
            $from_cookie = [];
        }
        $res = [];
        foreach ($this->grid->getConfig()->getColumns() as $column) {
            $name = $column->getName();
            if (isset($from_cookie[$name]) and $from_cookie[$name]) {
                $res[$name] = true;
            } else {
                $res[$name] = !in_array($name, $this->getHiddenByDefault());
            }
        }
        return $res;
    }

    public function getId($name)
    {
        if ($name) {
            $name = "-$name";
        }
        $grid_name = $this->grid->getConfig()->getName();
        return "{$grid_name}-columns_hider{$name}";
    }

    public function prepare()
    {
        parent::prepare();
        $visible = $this->getColumnsVisibility();
        foreach($this->grid->getConfig()->getColumns() as $column) {
            if (!$visible[$column->getName()]) {
                $column->hide();
            }
        }
    }

}