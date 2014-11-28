<?php
namespace Nayjest\Grids\Components;

use Nayjest\Grids\Components\Base\IComponent;
use Nayjest\Grids\Components\Base\IRenderableComponent;
use Nayjest\Grids\Components\Base\RenderableComponent;
use Nayjest\Grids\Components\Base\TComponent;

class ShowingRecords extends RenderableComponent {

    protected $template = '*.components.showing_records';

    /**
     * Adds $from, $to, $total to view data
     * @return mixed
     */
    protected function getViewData()
    {
        $paginator = $this
            ->grid
            ->getConfig()
            ->getDataProvider()
            ->getPaginator();
        $from = $paginator->getFrom();
        $to = $paginator->getTo();
        $total = $paginator->getTotal();

        return parent::getViewData() + compact('from','to','total');
    }
}