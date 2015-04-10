<?php
namespace Nayjest\Grids\Components;
use Illuminate\Foundation\Application;

/**
 * Class TFoot
 *
 * The component for rendering TFOOT html tag inside grid.
 *
 * @package Nayjest\Grids\Components
 */
class TFoot extends HtmlTag
{
    const NAME = 'tfoot';

    /**
     * Returns default set of child components.
     *
     * @return \Nayjest\Grids\Components\Base\ComponentInterface[]
     */
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
