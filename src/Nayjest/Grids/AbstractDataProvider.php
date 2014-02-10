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

    protected $requiredColumns;

    public function addFilter(FilterInterface $filter)
    {
        $this->filters[] = $filter;
    }

    public function setSorter(SorterInterface $sorter)
    {
        $this->sorter = $sorter;
    }

//    public function setRequiredColumns(array $columns)
//    {
//        $this->requiredColumns = $columns;
//    }
//
//    protected function getRequiredColumns()
//    {
//        return $this->requiredColumns;
//    }

} 