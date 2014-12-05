<?php
namespace Nayjest\Grids\Components;

class TFoot extends HtmlTag
{
    protected function getDefaultComponents()
    {
        return [
            (new OneCellRow)
                ->addComponent(new Pager)
        ];
    }
}