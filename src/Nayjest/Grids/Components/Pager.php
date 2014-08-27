<?php
namespace Nayjest\Grids\Components;


use Nayjest\Grids\Components\Base\IRenderableComponent;
use Nayjest\Grids\Components\Base\TRenderableComponent;
use Nayjest\Grids\ArrayDataRow;
use Nayjest\Grids\DataProvider;
use Nayjest\Grids\DataRow;
use Nayjest\Grids\FieldConfig;
use Nayjest\Grids\IdFieldConfig;
use Nayjest\Grids\Grid;
use Event;
use Illuminate\Support\Collection;

class Pager implements IRenderableComponent
{
    use TRenderableComponent {
        TRenderableComponent::initialize as protected initializeComponent;
    }

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