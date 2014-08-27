<?php
namespace Nayjest\Grids\Components;

use Nayjest\Grids\Components\Base\RenderableComponent;

class Pager extends RenderableComponent
{

    protected $template = '*.components.pager';
    protected $name = 'pager';

    public function links()
    {
        return $this->grid->getConfig()
            ->getDataProvider()
            ->getPaginator()
            ->appends(
                $this->grid->getInputProcessor()->getKey(),
                $this->grid->getInputProcessor()->getInput()
            )
            ->links();
    }

}