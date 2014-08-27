<?php
namespace Nayjest\Grids\Components;

use Nayjest\Grids\Components\Base\IRegistry;
use Nayjest\Grids\Components\Base\IRenderableComponent;
use Nayjest\Grids\Components\Base\TRenderableRegistry;

class Footer implements IRenderableComponent, IRegistry
{
    use TRenderableRegistry;

    const SECTION_BEGIN = 'footer.section_begin';
    const SECTION_END = 'footer.section_end';
    const SECTION_IN_ROW = null;

    public function __construct()
    {
        $this->template = '*.components.footer';
        $this->name = 'footer';
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