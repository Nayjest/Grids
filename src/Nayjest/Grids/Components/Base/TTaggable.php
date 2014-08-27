<?php
namespace Nayjest\Grids\Components\Base;


trait TTaggable
{
    protected $tags = [];

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