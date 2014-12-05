<?php
namespace Nayjest\Grids\Components;

class THead extends HtmlTag
{
    const NAME = 'thead';

    /**
     * Components in registry by default
     *
     * @return array
     */
    protected function getDefaultComponents()
    {
        return [
            new ColumnHeadersRow,
            new FiltersRow
        ];
    }
}