<?php
namespace Nayjest\Grids\Components\Base;

/**
 * Class RenderableRegistry
 *
 * Base class for components that can hold children components and be rendered.
 *
 * @package Nayjest\Grids\Components\Base
 */
class RenderableRegistry implements
    RenderableComponentInterface,
    RegistryInterface
{
    use TComponent;
    use TRegistry;
    use TComponentView {
        TComponentView::render as protected renderTemplate;
    }

    const SECTION_BEGIN = 'begin';
    const SECTION_END = 'end';
    const SECTION_BEFORE = 'before';
    const SECTION_AFTER = 'after';

    /**
     * Renders components related to specified section.
     *
     * By default components without specified section will be rendered.
     *
     * @param string|null $sectionName
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

    /**
     * Returns components filtered by section name.
     *
     * @param string $sectionName
     * @return \Illuminate\Support\Collection
     */
    public function getSectionComponents($sectionName)
    {
        return $this->getComponents()->filter(
            function (ComponentInterface $component) use ($sectionName) {
                return $component instanceof RenderableComponentInterface
                && $component->getRenderSection() === $sectionName;
            }
        );
    }

    /**
     * Wraps content with outside components (components that have 'before' or 'after' render_section value).
     *
     * @param string $output
     * @return string
     */
    protected function wrapWithOutsideComponents($output)
    {
        return $this->renderComponents(self::SECTION_BEFORE)
        . $output
        . $this->renderComponents(self::SECTION_AFTER);
    }

    /**
     * Renders inner components.
     *
     * @return string
     */
    protected function renderInnerComponents()
    {
        return $this->renderComponents(self::SECTION_BEGIN)
        . $this->renderComponents()
        . $this->renderComponents(self::SECTION_END);
    }

    /**
     * Renders component.
     *
     * @return string
     */
    public function render()
    {
        $this->is_rendered = true;
        return $this->wrapWithOutsideComponents(
            $this->getTemplate()
                ? $this->renderTemplate()
                : $this->renderInnerComponents()
        );
    }
}

