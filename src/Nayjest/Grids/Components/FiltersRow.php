<?php
namespace Nayjest\Grids\Components;

use Nayjest\Grids\Components\Base\RenderableRegistry;

class FiltersRow extends RenderableRegistry
{
    protected $template = '*.components.filters_row';
    protected $name = 'filters_row';
    protected $render_section = Header::SECTION_END;
}