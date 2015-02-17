<?php
namespace Nayjest\Grids\Components\Base;

/**
 * Class RenderableRegistry
 *
 * Base class for components that can hold children components and be rendered
 *
 * @package Nayjest\Grids\Components\Base
 */
class RenderableRegistry implements IRenderableComponent, IRegistry
{
    use TComponent;
    use TRegistry;
    use TRegistryView;
}