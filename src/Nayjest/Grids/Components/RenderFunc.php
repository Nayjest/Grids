<?php
namespace Nayjest\Grids\Components;

use Illuminate\Pagination\Paginator;
use Nayjest\Grids\Components\Base\RenderableComponent;
use Nayjest\Grids\Grid;

class RenderFunc extends RenderableComponent
{

    /** @var  callable */
    protected $func;

    /**
     * @param null|callable $func
     */
    public function __construct($func = null)
    {
        if ($func) $this->setFunc($func);
    }

    /**
     * @param callable $func
     */
    public function setFunc($func)
    {
        $this->func = $func;
    }

    public function render()
    {
        return call_user_func($this->func, $this->grid);
    }

}