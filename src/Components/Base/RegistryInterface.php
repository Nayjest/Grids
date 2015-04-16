<?php
namespace Nayjest\Grids\Components\Base;

/**
 * Interface RegistryInterface
 *
 * Interface of Grid components registry
 *
 * @package Nayjest\Grids\Components\Base
 */
interface RegistryInterface
{
    /**
     * Returns collection of attached components.
     *
     * @return \Illuminate\Support\Collection|ComponentInterface[]|array
     */
    public function getComponents();

    /**
     * Returns child component
     * with specified name or null if component not found.
     *
     * @param string $name
     * @return ComponentInterface|null
     */
    public function getComponentByName($name);

    /**
     * Adds component to collection.
     *
     * @param ComponentInterface $component
     * @return $this
     */
    public function addComponent(ComponentInterface $component);

    /**
     * Sets children components collection.
     *
     * @param \Illuminate\Support\Collection|ComponentInterface[]|array $components
     * @return $this
     */
    public function setComponents($components);

    /**
     * Adds components to collection.
     *
     * @param \Illuminate\Support\Collection|ComponentInterface[]|array $components
     * @return $this
     */
    public function addComponents($components);

    /**
     * Creates component be class name,
     * attaches it to children collection
     * and returns this component as result.
     *
     * @param string $class
     * @return ComponentInterface
     */
    public function makeComponent($class);
}
