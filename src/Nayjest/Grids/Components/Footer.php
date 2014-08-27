<?php
namespace Nayjest\Grids\Components;

use Nayjest\Grids\Components\Base\RenderableRegistry;

class Footer extends RenderableRegistry
{
    const SECTION_BEGIN = 'footer.section_begin';
    const SECTION_END = 'footer.section_end';
    const SECTION_IN_ROW = null;

    protected $template = '*.components.footer';
    protected $name = 'footer';

    public function getBeginComponents()
    {
        return $this->getSectionComponents(self::SECTION_BEGIN);
    }

    public function getEndComponents()
    {
        return $this->getSectionComponents(self::SECTION_END);
    }

    public function getRowComponents()
    {
        return $this->getSectionComponents(self::SECTION_IN_ROW);
    }
}