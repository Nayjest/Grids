<?php
namespace Nayjest\Grids\Components\Base;

interface ITaggable
{
    public function getTags();

    public function setTags(array $tagNames);

    public function hasTag($tagName);

    public function hasTags(array $tagNames);
}