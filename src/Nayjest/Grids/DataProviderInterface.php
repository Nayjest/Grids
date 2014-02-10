<?php
namespace Nayjest\Grids;

use Nayjest\Grids\Sorters\SorterInterface;
use Nayjest\Grids\Filters\FilterInterface;

interface DataProviderInterface
{
    /**
     * @return int
     */
    public function getRecordsCount();

    public function fetch($offset = 0, $limit = null);

    public function addFilter(FilterInterface $filter);

    public function setSorter(SorterInterface $sorter);

    //public function setRequiredColumns(array $columnNames);

} 