<?php
namespace Nayjest\Grids\Components\Base;

use Nayjest\Grids\Grid;

trait TComponent
{
    protected $parent;

    /** @var Grid */
    protected $grid;

    protected $name;

    protected $tags = [];

    public function attachTo(IRegistry $parent)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function initialize(Grid $grid)
    {
        $this->grid = $grid;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setTags(array $tags)
    {
        $this->tags = $tags;
    }

    public function hasTag($tag_name)
    {
        return in_array($tag_name, $this->tags);
    }

    public function hasTags(array $tag_names)
    {
        foreach ($tag_names as $tag) {
            if ($this->hasTag($tag)) continue;
            return false;
        }
        return true;
    }
}