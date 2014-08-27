<?php
namespace Nayjest\Grids\Components\Base;

class RenderableRegistry implements IRenderableComponent, IRegistry
{
    use TComponent;
    use TRegistry;
    use TRegistryView;
}