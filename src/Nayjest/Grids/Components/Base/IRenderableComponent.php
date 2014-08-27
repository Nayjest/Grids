<?php
namespace Nayjest\Grids\Components\Base;


interface IRenderableComponent extends IRenderable, IComponent
{
    public function getRenderSection();

    public function setRenderSection($section_name);
}