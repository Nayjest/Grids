<?php
namespace Nayjest\Grids\Components\Base;


interface IRenderable
{
    public function render();

    public function getTemplate();

    public function setTemplate($template);

    public function __toString();

    public function isRendered();
}