<?php
namespace Nayjest\Grids\Components;

use Nayjest\Grids\Components\Base\RenderableRegistry;

class Container extends RenderableRegistry
{
    protected $html_tags = ['div'];

    public function setHtmlTags(array $tags)
    {
        $this->html_tags = $tags;
        return $this;
    }

    public function render()
    {
        $before = '';
        $after = '';
        foreach($this->html_tags as $tag) {
            $before.="<$tag>";
            $after = "</$tag>".$after;
        }
        return $before . $this->renderComponents() . $after;
    }
}
