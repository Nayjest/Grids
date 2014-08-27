<?php
namespace Nayjest\Grids\Components;

use Nayjest\Grids\Components\Base\IRegistry;
use Nayjest\Grids\Components\Base\IRenderableComponent;
use Nayjest\Grids\Components\Base\TRenderableRegistry;

class FiltersRow implements IRenderableComponent, IRegistry
{
    use TRenderableRegistry;

    public function __construct()
    {
        $this->template = '*.components.filters_row';
        $this->name = 'filters_row';
        $this->render_section = Header::SECTION_END;
    }
}