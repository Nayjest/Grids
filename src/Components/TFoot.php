<?php
namespace Nayjest\Grids\Components;

class TFoot extends HtmlTag
{
    const NAME = 'tfoot';

    protected function getDefaultComponents()
    {
        return [
            (new OneCellRow)
                ->addComponent(new Pager)
        ];
    }
}