<?php
namespace Nayjest\Grids;

use Illuminate\Support\Collection;

class Filtering
{
    /** @var Grid */
    protected $grid;

    protected $filters;

    public function __construct(Grid $grid)
    {
        $this->grid = $grid;
    }

    /**
     * @return Collection|Filter[]
     */
    protected function getFilters()
    {
        if ($this->filters === null) {
            $this->createFilters();
        }
        return $this->filters;
    }

    protected function createFilters()
    {
        $filters = [];
        foreach ($this->grid->getConfig()->getColumns() as $column) {
            if ($column->hasFilters()) {
                foreach ($column->getFilters() as $filterConfig) {
                    $filters[$filterConfig->getId()] = new Filter(
                        $filterConfig,
                        $column,
                        $this->grid
                    );
                }
            }
        }
        $this->filters = Collection::make($filters);
    }

    public function available()
    {
        return !$this->getFilters()->isEmpty();
    }

    public function apply()
    {
        foreach ($this->getFilters() as $filter) {
            $filter->apply();
        }
    }

    public function getFilter($idOrConfig)
    {
        if ($idOrConfig instanceof FilterConfig) {
            $idOrConfig = $idOrConfig->getId();
        }
        return $this->getFilters()[$idOrConfig];
    }

    public function render($filterIdOrConfig)
    {
        return $this->getFilter($filterIdOrConfig)->render();
    }
}
