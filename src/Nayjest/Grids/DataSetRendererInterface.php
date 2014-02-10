<?php
namespace Nayjest\Grids;

use Illuminate\Support\Contracts\RenderableInterface;

interface DataSetRendererInterface extends RenderableInterface
{
    public function addProvider(DataProviderInterface $provider, $name = null);

    //public function setSorters();

    //public function setFilters();
} 