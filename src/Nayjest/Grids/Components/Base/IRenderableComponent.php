<?php
namespace Nayjest\Grids\Components\Base;


interface IRenderableComponent extends IRenderable, IComponent
{
    /**
     * @return string|null
     */
    public function getRenderSection();

    /**
     * @param string|null $sectionName
     * @return $this
     */
    public function setRenderSection($sectionName);
}