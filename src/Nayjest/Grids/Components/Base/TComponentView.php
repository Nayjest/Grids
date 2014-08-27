<?php
namespace Nayjest\Grids\Components\Base;

trait TComponentView
{
    use TRenderable {
        TRenderable::getTemplate as private getTemplateInternal;
    }

    protected $render_section;

    protected function getViewData()
    {
        return $this->grid->getViewData() + [
            'component' => $this
        ];
    }

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