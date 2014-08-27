<?php
namespace Nayjest\Grids\Components\Base;

interface ITaggable
{
    public function getTags();

    public function setTags(array $tag_names);

    public function hasTag($tag_name);

    public function hasTags(array $tag_names);
}