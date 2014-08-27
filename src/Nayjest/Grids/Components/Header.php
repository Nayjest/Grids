<?php
namespace Nayjest\Grids\Components;

use Nayjest\Grids\Components\Base\RenderableRegistry;

class Header extends RenderableRegistry
{
    const SECTION_BEGIN = 'header.section_begin';
    const SECTION_END = 'header.section_end';
    const SECTION_IN_ROW = null;

    protected $template = '*.components.header';
    protected $name = 'header';

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