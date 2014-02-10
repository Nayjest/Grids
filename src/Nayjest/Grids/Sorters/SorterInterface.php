<?php
namespace Nayjest\Grids\Sorters;


use Nayjest\Grids\ColumnInterface;

interface SorterInterface
{

    public function sort(&$src);

    //public function setColumn(ColumnInterface $column);

    public function setOrder($order);

} 