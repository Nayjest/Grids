<?php
namespace Nayjest\Grids\Sorters;

use \Closure;

class ArraySorter extends AbstractColumnSorter
{
    protected $comparisonFunction = null;

    public function setComparisonFunction(\Closure $func)
    {
        $this->comparisonFunction = $func;
    }

    public function getComparisonFunction()
    {
        return $this->comparisonFunction ? : [$this, 'defaultComparison'];
    }

    protected function defaultComparison($a, $b)
    {
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }

    public function sort(&$src)
    {
        $comparisonFunction = $this->getComparisonFunction();
        $columnName = $this->getColumnName();

        $sorterFunction = function ($a, $b) use ($comparisonFunction, $columnName) {
            $res = call_user_func($comparisonFunction, $a[$columnName], $b[$columnName]);
            return ($this->getOrder() == self::ASC) ? $res : -$res;
        };
        usort($src, $sorterFunction);
    }
} 