<?php
namespace Nayjest\Grids\Components\Base;

use View;

/**
 * Trait TRenderable
 *
 * Default implementation of rendering facilities for grid component, etc.
 * @todo Avoid usage of Laravel Facade aliases (?)
 * @todo Absence of getViewData isn't convenient (?)
 *
 * @package Nayjest\Grids\Components\Base
 */
trait TRenderable
{

    protected $template;

    protected $is_rendered = false;

    public function render()
    {
        $this->is_rendered = true;
        return View::make(
            $this->getTemplate(),
            $this->getViewData()
        )->render();
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    public function isRendered()
    {
        return $this->is_rendered;
    }

    public function __toString()
    {
        return (string)$this->render();
    }
}