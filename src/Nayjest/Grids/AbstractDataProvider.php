<?php
namespace Nayjest\Grids;

use Nayjest\Grids\Sorters\SorterInterface;
use Nayjest\Grids\Filters\FilterInterface;

class AbstractDataProvider
{

    /**
     * @var FilterInterface[]
     */
    protected $filters = [];

    /**
     * @var SorterInterface
     */
    protected $sorter;

    public function addFilter(FilterInterface $filter)
    {
        $this->filters[] = $filter;
    }

    public function setSorter(SorterInterface $sorter)
    {
        $this->sorter = $sorter;
    }


} 