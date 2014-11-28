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
     * @return \Illuminate\Support\Collection|IComponent[]|array
     */
    public function getComponents();

    /**
     * @param string $name
     * @return IComponent|null
     */
    public function getComponentByName($name);

    /**
     * @param IComponent $component
     * @return $this
     */
    public function addComponent(IComponent $component);

    /**
     * @param \Illuminate\Support\Collection|IComponent[]|array $components
     * @return mixed
     */
    public function setComponents($components);

    /**
     * @param string $class
     * @return IComponent
     */
    public function makeComponent($class);

}