<?php
namespace Nayjest\Grids\Components;

class SelectFilter extends Filter
{
    protected $variants = [];

    protected $template = '*.components.filters.select';

    public function getVariants()
    {
        return $this->variants;
    }

    public function setVariants(array $variants)
    {
        $this->variants = $variants;
        return $this;
    }
}
