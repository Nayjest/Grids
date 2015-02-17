<?php
namespace Nayjest\Grids\Components;
use Illuminate\Foundation\Application;

class TFoot extends HtmlTag
{
    const NAME = 'tfoot';

    protected function getDefaultComponents()
    {
        if (version_compare(Application::VERSION, '5', '<')) {
            $pagerClass = 'Nayjest\Grids\Components\Pager';
        } else {
            $pagerClass = 'Nayjest\Grids\Components\Laravel5\Pager';
        }
        return [
            (new OneCellRow)
                ->addComponent(new $pagerClass)
        ];
    }
}