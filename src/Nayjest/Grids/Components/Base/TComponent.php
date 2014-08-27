<?php
namespace Nayjest\Grids\Components\Base;

use Nayjest\Grids\Grid;

trait TComponent
{
    use TTaggable;

    protected $parent;

    /** @var Grid */
    protected $grid;

    protected $name;

    public function attachTo(IRegistry $parent)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function initialize(Grid $grid)
    {
        $this->grid = $grid;
        if (method_exists($this, 'initializeComponents')) {
            $this->initializeComponents($grid);
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function prepare()
    {
        if (method_exists($this, 'initializeComponents')) {
            $this->prepareComponents();
        }
    }

}