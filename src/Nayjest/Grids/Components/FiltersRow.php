<?php
namespace Nayjest\Grids\Components;

use Nayjest\Grids\Components\Base\RenderableRegistry;

/**
 * Class FiltersRow
 *
 * provides additional render sections for columns: 'filters_row_column_<name>'
 *
 * @package Nayjest\Grids\Components
 */
class FiltersRow extends RenderableRegistry
{
    protected $template = '*.components.filters_row';
    protected $name = 'filters_row';
    protected $render_section = THead::SECTION_END;
}