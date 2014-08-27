<?php
namespace Nayjest\Grids\Components;

use Nayjest\Grids\Components\Base\IRegistry;
use Nayjest\Grids\Components\Base\IRenderableComponent;
use Nayjest\Grids\Components\Base\TComponent;
use Nayjest\Grids\Components\Base\TRegistry;
use Nayjest\Grids\Components\Base\TRegistryView;

class Footer implements IRenderableComponent, IRegistry
{
    use TComponent;
    use TRegistry;
    use TRegistryView;

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