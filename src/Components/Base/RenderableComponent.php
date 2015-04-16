<?php
namespace Nayjest\Grids\Components\Base;

/**
 * Class RenderableComponent
 *
 * Base class for components that can be rendered.
 *
 * @package Nayjest\Grids\Components\Base
 */
class RenderableComponent implements RenderableComponentInterface
{
    use TComponent;
    use TComponentView;
}
