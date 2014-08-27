<?php
namespace Nayjest\Grids\Components\Base;

use Nayjest\Grids\Grid;

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