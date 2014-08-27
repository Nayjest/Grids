<?php
namespace Nayjest\Grids\Components\Base;

use View;

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
        );
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