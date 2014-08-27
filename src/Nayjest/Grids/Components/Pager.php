<?php
namespace Nayjest\Grids\Components;

use Nayjest\Grids\Components\Base\RenderableComponent;

class Pager extends RenderableComponent
{

    public function render() {
        return $this->links();
    }

    protected function links()
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