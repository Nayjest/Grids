<?php
namespace Nayjest\Grids;

use Illuminate\Support\Collection;

/**
 * Class FieldConfig
 *
 * This class describes grid column.
 *
 * @package Nayjest\Grids
 */
class FieldConfig
{
    /**
     * Field name.
     *
     * @var string
     */
    protected $name;

    /**
     * Text label that will be rendered in table header.
     *
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

    /**
     * @var string|null
     */
    protected $sortable_column;

    protected $sorting;

    /** @var  Collection|FilterConfig[] */
    protected $filters;

    /** @var  callable */
    protected $callback;

    /**
     * @var array
     */
    protected $attributes = [];

    protected $is_hidden = false;

    /**
     * Constructor.
     *
     * @param string|null $name column unique name for internal usage
     * @param string|null $label column label
     */
    public function __construct($name = null, $label = null)
    {
        if ($name !== null) {
            $this->setName($name);
        }
        if ($label !== null) {
            $this->setLabel($label);
        }
    }

    /**
     * Returns column order.
     *
     * This property used to to identify column position in grid.
     *
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Sets column order.
     *
     * This property used to to identify column position in grid.
     *
     * @param $order
     * @return $this
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Returns field name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets field name.
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Returns the sortable name.
     *
     * @returns string
     */
    public function getSortableName()
    {
        if ($this->sortable_column) {
            return $this->sortable_column;
        }

        return $this->getName();
    }

    /**
     * Sets field name.
     *
     * @param string|null $name
     * @return $this|string
     */
    public function name($name = null)
    {
        if (is_null($name)) {
            return $this->name;
        }

        $this->name = $name;

        return $this;
    }

    /**
     * Returns true if column is hidden.
     *
     * @return bool
     */
    public function isHidden()
    {
        return $this->is_hidden;
    }

    /**
     * Makes column hidden.
     *
     * @return $this
     */
    public function hide()
    {
        $this->is_hidden = true;

        return $this;
    }

    /**
     * Makes column visible.
     *
     * @return $this
     */
    public function show()
    {
        $this->is_hidden = false;

        return $this;
    }

    /**
     * Returns text label that will be rendered in table header.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label ?: ucwords(str_replace(['-', '_', '.'], ' ', $this->name));
    }

    /**
     * Sets text label that will be rendered in table header.
     *
     * @param string $label
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Sets text label that will be rendered in table header.
     *
     * @param string|null $label
     * @return $this|string
     */
    public function label($label = null)
    {
        if (is_null($label)) {
            return $this->label;
        }

        $this->label = $label;

        return $this;
    }

    /**
     * Returns true if column is sortable (sorting controls must be rendered).
     *
     * @return bool
     */
    public function isSortable()
    {
        return $this->is_sortable;
    }

    /**
     * Allows to enable or disable sorting controls for column.
     *
     * @param boolean $isSortable
     * @param string|null $sortableColumn
     * @return $this
     */
    public function setSortable($isSortable, $sortableColumn = null)
    {
        $this->sortable_column = $sortableColumn;
        $this->is_sortable = $isSortable;

        return $this;
    }

    /**
     * Allows to enable or disable sorting controls for column.
     *
     * @param boolean|null $isSortable
     * @param string|null $sortableColumn by default we use the field name to sort, but in some cases may be different
     * name, for example, the relation column is 'profile.nickname' and inner join is 'profiles.nickname'.
     * @return $this|boolean
     */
    public function sortable($isSortable, $sortableColumn = null)
    {
        if (is_null($isSortable)) {
            return $this->is_sortable;
        }

        $this->sortable_column = $sortableColumn;
        $this->is_sortable = $isSortable;

        return $this;
    }

    /**
     * Returns current sorting order
     * or null if table rows are not sorted using this column.
     *
     * @return null|string null|Grid::SORT_ASC|Grid::SORT_DESC
     */
    public function getSorting()
    {
        return $this->sorting;
    }

    /**
     * Allows to specify sorting by this column for data rows.
     *
     * @param null|string $sortOrder null|Grid::SORT_ASC|Grid::SORT_DESC
     * @return $this
     */
    public function setSorting($sortOrder)
    {
        $this->sorting = $sortOrder;

        return $this;
    }

    /**
     * Allows to specify sorting by this column for data rows.
     *
     * @param null|string|boolean $sortOrder null|Grid::SORT_ASC|Grid::SORT_DESC|false
     * @return $this
     */
    public function sorting($sortOrder = false)
    {
        if ($sortOrder === false) {
            return $this->sorting;
        }

        $this->sorting = $sortOrder;

        return $this;
    }

    /**
     * Returns true if data rows are sorted ascending using this column.
     *
     * @return bool
     */
    public function isSortedAsc()
    {
        return $this->sorting === Grid::SORT_ASC;
    }

    /**
     * Returns true if data rows are sorted descending using this column.
     *
     * @return bool
     */
    public function isSortedDesc()
    {
        return $this->sorting === Grid::SORT_DESC;
    }

    /**
     * Allows to set callback function that will render
     * content of table cells for this column.
     *
     * @param callable $callback
     * @return $this
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;

        return $this;
    }

    /**
     * Returns function that will render
     * content of table cells for this column if specified.
     *
     * @return callable|null
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * Allows to set callback function that will render
     * content of table cells for this column.
     *
     * @param string|null $callback
     * @return $this|callable|null
     */
    public function callback($callback = null)
    {
        if (is_null($callback)) {
            return $this->callback;
        }

        $this->callback = $callback;

        return $this;
    }

    /**
     * Allows to specify filtering controls for column.
     *
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

    /**
     * Allows to specify filtering controls for column.
     *
     * @param Collection|FilterConfig[]|null $filters
     * @return $this|Collection|FilterConfig[]
     */
    public function filters($filters = null)
    {
        if (is_null($filters)) {
            return $this->filters;
        }

        $this->filters = Collection::make($filters);
        foreach ($this->filters as $filterConfig) {
            $filterConfig->attach($this);
        }

        return $this;
    }

    /**
     * Allows to add filtering control to column.
     *
     * @param FilterConfig $filter
     * @return $this
     */
    public function addFilter(FilterConfig $filter)
    {
        $this->getFilters()->push($filter);
        $filter->attach($this);

        return $this;
    }

    /**
     * Allows to add filtering control to column.
     *
     * @param FilterConfig $filter
     * @return $this
     */
    public function filter(FilterConfig $filter)
    {
        $this->addFilter($filter);

        return $this;
    }

    /**
     * Creates instance of filtering control configuration
     * and binds it to the column.
     *
     * @param string $class
     * @return FilterConfig
     */
    public function makeFilter($class = '\Nayjest\Grids\FilterConfig')
    {
        $filter = new $class;
        $this->addFilter($filter);

        return $filter;
    }

    /**
     * Returns true if any filtering controls specified for the column.
     *
     * @return bool
     */
    public function hasFilters()
    {
        return !$this->getFilters()->isEmpty();
    }

    /**
     * Returns list of filtering controls specified for the column.
     *
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
     * @param DataRowInterface $row
     * @return mixed
     */
    public function getValue(DataRowInterface $row)
    {
        if ($function = $this->getCallback()) {
            return call_user_func($function, $row->getCellValue($this), $row);
        } else {
            return $row->getCellValue($this);
        }
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}
