<?php
namespace Nayjest\Grids\Components\Base;

use Nayjest\Grids\Grid;

/**
 * Interface IComponent
 *
 * Interface of Grid component.
 * Basically, component is an object that can be attached
 * to grid components hierarchy and react to initialize & prepare calls.
 *
 * @package Nayjest\Grids\Components\Base
 */
interface IComponent extends ITaggable
{
    /**
     * @param IRegistry $parent
     * @return null
     */
    public function attachTo(IRegistry $parent);

    /**
     * @return IRegistry
     */
    public function getParent();

    /**
     * @param Grid $grid
     * @return null
     */
    public function initialize(Grid $grid);

    public function prepare();

    /**
     * @return string|null
     */
    public function getName();

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name);
}