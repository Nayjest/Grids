<?php
namespace Nayjest\Grids\Components;

use Nayjest\Grids\Components\Base\RenderableComponent;

class ColumnsHider extends RenderableComponent
{

    protected $template = '*.components.columns_hider';

    protected $name = 'columns_hider';

    public function getId($name)
    {
        if ($name) {
            $name = "-$name";
        }
        $grid_name = $this->grid->getConfig()->getName();
        return "{$grid_name}-columns_hider{$name}";
    }

}