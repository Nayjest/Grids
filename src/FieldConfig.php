<?php
namespace Nayjest\Grids;

use Illuminate\Support\Collection;

class FieldConfig
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var int
     */
    protected $order = 0;

    /**
     * @var bool
     */
    protected $is_sortable = false;

    protected $sorting = null;

    /** @var  Collection|FilterConfig[] */
    protected $filters;

    /** @var  callable */
    protected $callback;

    protected $is_hidden = false;

    /**
     * @param string|null $name column unique name for internal usage
     * @param string|null $label column label
     */
    public function __construct($name = null, $label = null)
    {
        if ($name) $this->setName($name);
        if ($label) $this->setLabel($label);
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function isHidden()
    {
        return $this->is_hidden;
    }

    public function hide()
    {
        $this->is_hidden = true;
        return $this;
    }

    public function show()
    {
        $this->is_hidden = false;
        return $this;
    }

    public function getLabel()
    {
        return $this->label ? : $this->name;
    }

    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    public function isSortable()
    {
        return $this->is_sortable;
    }

    /**
     * @param boolean $isSortable
     * @return $this
     */
    public function setSortable($isSortable)
    {
        $this->is_sortable = $isSortable;
        return $this;
    }

    /**
     * @return null|string null|Grid::SORT_ASC|Grid::SORT_DESC
     */
    public function getSorting()
    {
        return $this->sorting;
    }

    /**
     * @param null|string $sortOrder null|Grid::SORT_ASC|Grid::SORT_DESC
     * @return $this
     */
    public function
    setSorting($sortOrder)
    {
        $this->sorting = $sortOrder;
        return $this;
    }

    public function isSortedAsc()
    {
        return $this->sorting === Grid::SORT_ASC;
    }

    public function isSortedDesc()
    {
        return $this->sorting === Grid::SORT_DESC;
    }

    /**
     * @param callable $callback
     * @return $this
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;
        return $this;
    }

    /**
     * @return callable
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @param Collection|FilterConfig[] $filters
     * @return $this
     */
    public function setFilters($filters)
    {
        $this->filters = Collection::make($filters);
        foreach ($this->filters as $filterConfig) {
            $filterConfig->attach($this);
        }

        return $this;
    }

    public function addFilter(FilterConfig $filter)
    {
        $this->getFilters()->push($filter);
        $filter->attach($this);
        return $this;
    }

    /**
     * @param string $class
     * @return FilterConfig
     */
    public function makeFilter($class = '\Nayjest\Grids\FilterConfig')
    {
        $filter = new $class;
        $this->addFilter($filter);
        return $filter;
    }

    public function hasFilters()
    {
        return !$this->getFilters()->isEmpty();
    }

    /**
     * @return Collection|FilterConfig[]
     */
    public function getFilters()
    {
        if (null === $this->filters) {
            $this->filters = new Collection();
        }
        return $this->filters;
    }

    /**
     * @todo move to Field instance
     * @param DataRow $row
     * @return mixed
     */
    public function getValue(DataRow $row)
    {
        if ($function = $this->getCallback()) {
            return call_user_func($function, $row->getCellValue($this), $row);
        } else {
            return $row->getCellValue($this);
        }
    }

}