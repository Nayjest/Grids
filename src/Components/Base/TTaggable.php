<?php
namespace Nayjest\Grids\Components\Base;

trait TTaggable
{
    protected $tags = [];

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     * @return $this
     */
    public function setTags(array $tags)
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * @param string $tagName
     * @return bool
     */
    public function hasTag($tagName)
    {
        return in_array($tagName, $this->tags);
    }

    /**
     * @param array|string[] $tagNames
     * @return bool
     */
    public function hasTags(array $tagNames)
    {
        foreach ($tagNames as $tag) {
            if (!$this->hasTag($tag)) {
                return false;
            }
        }
        return true;
    }
}
