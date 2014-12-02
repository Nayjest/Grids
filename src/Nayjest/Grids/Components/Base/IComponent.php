<?php
namespace Nayjest\Grids\Components\Base;

use Nayjest\Grids\Grid;

/**
 * Interface IComponent
 *
 * Interface of Grid component.
 *
 * Basically, component is an object that can be attached
 * to grid components hierarchy and react to initialize & prepare calls.
 *
 * @package Nayjest\Grids\Components\Base
 */
interface IComponent extends ITaggable
{
    /**
     * Attaches component to registry
     *
     * @param IRegistry $parent
     * @return null
     */
    public function attachTo(IRegistry $parent);

    /**
     * Returns parent object
     *
     * @return IRegistry
     */
    public function getParent();

    /**
     * Initializes component with grid
     *
     * @param Grid $grid
     * @return null
     */
    public function initialize(Grid $grid);

    /**
     * Performs all required operations before rendering component
     *
     * @return mixed
     */
    public function prepare();

    /**
     * Returns component name
     *
     * @return string|null
     */
    public function getName();

    /**
     * Sets component name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name);
}