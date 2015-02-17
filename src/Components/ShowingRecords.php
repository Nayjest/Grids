<?php
namespace Nayjest\Grids\Components;

use Nayjest\Grids\Components\Base\RenderableComponent;

class ShowingRecords extends RenderableComponent
{

    protected $template = '*.components.showing_records';

    /**
     * Passing $from, $to, $total to view
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

        return parent::getViewData() + compact('from', 'to', 'total');
    }
}