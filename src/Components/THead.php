<?php
namespace Nayjest\Grids\Components;

/**
 * Class THead
 *
 * The component for rendering THEAD html tag inside grid.
 *
 * @package Nayjest\Grids\Components
 */
class THead extends HtmlTag
{
    const NAME = 'thead';

    /**
     * Returns default set of child components.
     *
     * @return \Nayjest\Grids\Components\Base\ComponentInterface[]
     */
    protected function getDefaultComponents()
    {
        return [
            new ColumnHeadersRow,
            new FiltersRow
        ];
    }
}
