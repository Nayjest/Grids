<?php
namespace Nayjest\Grids\Components\Base;

use View;

/**
 * Trait TRenderable
 *
 * Default implementation of rendering facilities for grid component, etc.
 *
 * @todo Avoid usage of Laravel Facade aliases (?)
 * @todo Absence of getViewData isn't convenient (?)
 *
 * @package Nayjest\Grids\Components\Base
 */
trait TRenderable
{
    /**
     * Name of view template.
     *
     * @var  string
     */
    protected $template;

    /** @var bool */
    protected $is_rendered = false;

    /**
     * Renders object.
     *
     * @return string
     */
    public function render()
    {
        $this->is_rendered = true;
        return View::make(
            $this->getTemplate(),
            $this->getViewData()
        )->render();
    }

    /**
     * Returns name of view template.
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Allows to specify view template.
     *
     * @param string $template
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * Returns true if object already was rendered.
     *
     * @return bool
     */
    public function isRendered()
    {
        return $this->is_rendered;
    }

    /**
     * Renders object when it is treated like a string.
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->render();
    }
}
