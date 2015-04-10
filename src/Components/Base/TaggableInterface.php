<?php
namespace Nayjest\Grids\Components\Base;

/**
 * Interface TaggableInterface
 * @package Nayjest\Grids\Components\Base
 */
interface TaggableInterface
{
    public function getTags();

    public function setTags(array $tagNames);

    public function hasTag($tagName);

    public function hasTags(array $tagNames);
}
