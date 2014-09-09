<?php
namespace Nayjest\Grids;

use Illuminate\Support\Collection;
use View;

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
                foreach ($column->getFilters() as $filter_config) {
                    $filters[$filter_config->getId()] = new Filter(
                        $filter_config,
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

    public function getFilter($id_or_config)
    {
        if ($id_or_config instanceof FilterConfig) {
            $id_or_config = $id_or_config->getId();
        }
        return $this->getFilters()[$id_or_config];
    }

    public function render($filter_id_or_config)
    {
        return $this->getFilter($filter_id_or_config)->render();
    }
} 