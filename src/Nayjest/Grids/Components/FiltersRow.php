<?php
namespace Nayjest\Grids\Components;

use Nayjest\Grids\Components\Base\IRegistry;
use Nayjest\Grids\Components\Base\IRenderableComponent;
use Nayjest\Grids\Components\Base\TComponent;
use Nayjest\Grids\Components\Base\TRegistry;
use Nayjest\Grids\Components\Base\TRegistryView;

class FiltersRow implements IRenderableComponent, IRegistry
{
    use TComponent;
    use TRegistry;
    use TRegistryView;

    public function __construct()
    {
        $this->template = '*.components.filters_row';
        $this->name = 'filters_row';
        $this->render_section = Header::SECTION_END;
    }
}