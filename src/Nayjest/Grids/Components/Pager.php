<?php
namespace Nayjest\Grids\Components;


use Nayjest\Grids\Components\Base\IRenderableComponent;
use Nayjest\Grids\Components\Base\TComponent;
use Nayjest\Grids\Components\Base\TComponentView;
use Event;

class Pager implements IRenderableComponent
{
    use TComponent;
    use TComponentView;

    public function __construct()
    {
        $this->template = '*.components.pager';
        $this->name = 'pager';
    }

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