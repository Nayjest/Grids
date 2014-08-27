<?php
namespace Nayjest\Grids\Components;

use Nayjest\Grids\Components\Base\RenderableRegistry;

class HtmlTag extends RenderableRegistry
{
    protected $tag_name = 'div';

    protected $content;

    protected $attributes = [];

    public function setTagName($name)
    {
        $this->tag_name = $name;
        return $this;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function setAttributes(array $attributes = [])
    {
        $this->attributes = $attributes;
        return $this;
    }


    public function render()
    {
        $out = "<$this->tag_name";
        foreach ($this->attributes as $key => $val) {
            $out .= " $key=\"$val\" ";
        }
        $out.= '>';
        $out.= $this->content?:$this->renderComponents();
        $out.= "</$this->tag_name>";
        return $out;
    }
}