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
    public function renderComponents($sectionName = null)
    {
        $output = '';
        $components = $this->getSectionComponents($sectionName);
        foreach ($components as $component) {
            $output .= $component->render();
        }
        return $output;
    }

    public function getSectionComponents($sectionName)
    {
        return $this->getComponents()->filter(
            function (ComponentInterface $component) use ($sectionName) {
                return $component instanceof RenderableComponentInterface && $component->getRenderSection() === $sectionName;
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