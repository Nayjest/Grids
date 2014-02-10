<?php
namespace Nayjest\Grids\Sorters;

use Nayjest\Grids\ColumnInterface;

abstract class AbstractSorter implements SorterInterface
{

    const ASC = 'asc';
    const DESC = 'desc';

    protected $order;

    abstract public function sort(&$src);

    public function getOrder()
    {
        return $this->order;
    }

    public function setOrder($order)
    {
        $this->order = $order;
    }
} 