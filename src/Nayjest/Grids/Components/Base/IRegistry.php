<?php
namespace Nayjest\Grids\Components\Base;

use Nayjest\Grids\Grid;

/**
 * Interface IRegistry
 *
 * Interface of Grid components registry
 *
 * @package Nayjest\Grids\Components\Base
 */
interface IRegistry
{
    /**
     * Returns collection of attached components
     *
     * @return \Illuminate\Support\Collection|IComponent[]|array
     */
    public function getComponents();

    /**
     * Returns child component with specified name or null if component not found
     *
     * @param string $name
     * @return IComponent|null
     */
    public function getComponentByName($name);

    /**
     * Adds component to collection
     *
     * @param IComponent $component
     * @return $this
     */
    public function addComponent(IComponent $component);

    /**
     * Sets children components collection
     *
     * @param \Illuminate\Support\Collection|IComponent[]|array $components
     * @return $this
     */
    public function setComponents($components);

    /**
     * Creates component be class name,
     * attaches it to children collection
     * and returns this component as result
     *
     * @param string $class
     * @return IComponent
     */
    public function makeComponent($class);

}