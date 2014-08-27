<?php
namespace Nayjest\Grids\Components\Base;

trait TRegistryView
{
    use TComponentView {
        TComponentView::render as protected renderTemplate;
    }

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
}