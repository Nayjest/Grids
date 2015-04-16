<?php
namespace Nayjest\Grids;

use Illuminate\Support\Collection;

/**
 * Class Filtering
 *
 * This class manages data filtering.
 *
 * @package Nayjest\Grids
 */
class Filtering
{
    /** @var Grid */
    protected $grid;

    protected $filters;

    /**
     * Constructor.
     *
     * @param Grid $grid
     */
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

    /**
     * Creates filter objects.
     */
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

    /**
     * Returns true is any filters available.
     *
     * @return bool
     */
    public function available()
    {
        return !$this->getFilters()->isEmpty();
    }

    /**
     * Applies filtering to data provider.
     */
    public function apply()
    {
        foreach ($this->getFilters() as $filter) {
            $filter->apply();
        }
    }

    /**
     * Returns filter instance.
     *
     * @param $idOrConfig
     * @return mixed|Filter
     */
    public function getFilter($idOrConfig)
    {
        if ($idOrConfig instanceof FilterConfig) {
            $idOrConfig = $idOrConfig->getId();
        }
        return $this->getFilters()[$idOrConfig];
    }

    /**
     * Renders filtering control.
     *
     * @param $filterIdOrConfig
     * @return string
     */
    public function render($filterIdOrConfig)
    {
        return $this->getFilter($filterIdOrConfig)->render();
    }
}
