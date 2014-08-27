<?php
namespace Nayjest\Grids\Components;

use Nayjest\Grids\Components\Base\IRegistry;
use Nayjest\Grids\Components\Base\IRenderableComponent;
use Nayjest\Grids\Components\Base\TRenderableRegistry;

class Header implements IRenderableComponent, IRegistry
{
    use TRenderableRegistry;

    const SECTION_BEGIN = 'header.section_begin';
    const SECTION_END = 'header.section_end';
    const SECTION_IN_ROW = null;

    public function __construct()
    {
        $this->template = '*.components.header';
        $this->name = 'header';
    }

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