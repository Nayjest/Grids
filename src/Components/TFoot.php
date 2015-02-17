<?php
namespace Nayjest\Grids\Components;
use Illuminate\Foundation\Application;
use Nayjest\Grids\Components\Pager as L4Pager;
use Nayjest\Grids\Components\Laravel5\Pager as L5Pager;
class TFoot extends HtmlTag
{
    const NAME = 'tfoot';

    protected function getDefaultComponents()
    {
        if (version_compare(Application::VERSION, '5', '<')) {
            $pagerClass = 'L4Pager';
        } else {
            $pagerClass = 'L5Pager';
        }
        return [
            (new OneCellRow)
                ->addComponent(new $pagerClass)
        ];
    }
}