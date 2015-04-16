<?php
namespace Nayjest\Grids\Components\Base;

use Nayjest\Grids\Grid;

/**
 * Class TComponent
 *
 * ComponentInterface interface implementation.
 *
 * @see Nayjest\Grids\Components\Base\ComponentInterface
 *
 * @package Nayjest\Grids\Components\Base
 */
trait TComponent
{
    use TTaggable;

    protected $parent;

    /** @var Grid */
    protected $grid;

    /** @var string|null */
    protected $name;

    /**
     * Attaches component to registry.
     *
     * @param RegistryInterface $parent
     * @return null
     */
    public function attachTo(RegistryInterface $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Returns parent object.
     *
     * @return RegistryInterface
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Initializes component with grid.
     *
     * @param Grid $grid
     * @return null
     */
    public function initialize(Grid $grid)
    {
        $this->grid = $grid;
        if (method_exists($this, 'initializeComponents')) {
            $this->initializeComponents($grid);
        }
    }

    /**
     * Returns component name.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets component name.
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Performs all required operations before rendering component.
     *
     * @return mixed
     */
    public function prepare()
    {
        if (method_exists($this, 'initializeComponents')) {
            $this->prepareComponents();
        }
    }
}
