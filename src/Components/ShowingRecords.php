<?php
namespace Nayjest\Grids\Components;

use Nayjest\Grids\Components\Base\RenderableComponent;

/**
 * Class ShowingRecords
 *
 * Renders text: Showing records $from — $to of $total
 *
 * @package Nayjest\Grids\Components
 */
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
        # Laravel 4
        if (method_exists($paginator, 'getFrom')) {
            $from = $paginator->getFrom();
            $to = $paginator->getTo();
            $total = $paginator->getTotal();
        # Laravel 5
        } else {
            $from = $paginator->firstItem();
            $to = $paginator->lastItem();
            $total = $paginator->total();
        }
        return parent::getViewData() + compact('from', 'to', 'total');
    }
}
