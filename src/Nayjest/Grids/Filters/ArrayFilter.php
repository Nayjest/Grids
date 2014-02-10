<?php
namespace Nayjest\Grids\Filters;

use \Closure;

class ArrayFilter implements FilterInterface
{
    protected $filterMethod;

    public function getFilterMethod()
    {
        return $this->filterMethod;
    }

    public function setFilterMethod(Closure $func)
    {
        $this->filterMethod = $func;
    }

    public function filter(&$src)
    {
        $src = array_filter($src, $this->getFilterMethod());
    }
} 