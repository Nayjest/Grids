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
    const NAME = 'filters_row';
    protected $template = '*.components.filters_row';
    protected $name = FiltersRow::NAME;
    protected $render_section = self::SECTION_END;
}
