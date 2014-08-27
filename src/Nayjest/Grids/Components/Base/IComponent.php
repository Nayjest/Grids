<?php
namespace Nayjest\Grids\Components\Base;

use Nayjest\Grids\Grid;

interface IComponent
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

    public function getTags();

    public function setTags(array $tag_names);

    public function hasTag($tag_name);

    public function hasTags(array $tag_names);

}