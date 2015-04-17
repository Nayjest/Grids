<?php
namespace Nayjest\Grids\Components;

use Nayjest\Grids\Components\Base\RenderableComponent;

/**
 * Class RenderFunc
 *
 * The component prints value returned by specified PHP function.
 *
 * @package Nayjest\Grids\Components
 */
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

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        return call_user_func($this->func, $this->grid, $this);
    }
}
