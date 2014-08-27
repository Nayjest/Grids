<?php
namespace Nayjest\Grids\Components\Base;

use View;

trait TRenderableRegistry
{
    use TRenderable {
        TRenderable::render as protected renderTemplate;
    }
    use TRegistry;

    protected $render_section;

    /**
     * @return string
     */
    public function renderComponents($section_name = null)
    {
        $output = '';
        $components = $this->getSectionComponents($section_name);
        foreach ($components as $component) {
            $output .= $component->render();
        }
        return $output;
    }

    public function getSectionComponents($section_name)
    {
        return $this->getComponents()->filter(
            function (IComponent $component) use ($section_name) {
                return $component instanceof IRenderableComponent and $component->getRenderSection() === $section_name;
            }
        );
    }

    /**
     * @return View|string
     */
    public function render()
    {
        if ($this->getTemplate()) {
            return $this->renderTemplate();
        } else {
            $this->is_rendered = true;
            return $this->renderComponents();
        }
    }

    protected function getViewData()
    {
        return $this->grid->getViewData() + [
            'component' => $this,
            'components' => $this->getComponents(),
        ];
    }

    /**
     * @todo copypaste from TRenderableComponent
     * @return string
     */
    public function getTemplate()
    {
        $grid_tpl = $this->grid->getConfig()->getTemplate();
        return str_replace('*.',"$grid_tpl.", $this->template);
    }

    public function getRenderSection()
    {
        return $this->render_section;
    }

    public function setRenderSection($section_name)
    {
        $this->render_section = $section_name;
        return $this;
    }

}