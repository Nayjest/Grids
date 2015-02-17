<?php
namespace Nayjest\Grids\Components\Base;

/**
 * Interface IRenderableComponent
 *
 * Interface for grid components that can be rendered
 *
 * @package Nayjest\Grids\Components\Base
 */
interface IRenderableComponent extends IRenderable, IComponent
{
    /**
     * Returns section (named placeholder in parent object markup) where component must be rendered
     *
     * @return string|null
     */
    public function getRenderSection();

    /**
     * Sets section (named placeholder in parent object markup) where component must be rendered
     *
     * @param string|null $sectionName
     * @return $this
     */
    public function setRenderSection($sectionName);
}