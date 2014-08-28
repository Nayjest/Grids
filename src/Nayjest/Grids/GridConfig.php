<?php
namespace Nayjest\Grids;

use Illuminate\Support\Collection;
use Nayjest\Grids\Components\Base\TComponent;
use Nayjest\Grids\Components\Base\TRegistry;
use Nayjest\Grids\Components\Base\IRegistry;

class GridConfig implements IRegistry
{
    use TRegistry;
    use TComponent;

    protected  $template = 'grids::default';

    /** @var FieldConfig[]|Collection  */
    protected  $columns = [];

    /** @var  DataProvider $data_provider */
    protected  $data_provider;

    protected $page_size = 50;

    /** @var Collection|FilterConfig[] $filters */
    protected $filters;

    /** @var int */
    protected $caching_time = 0;

    /**
     * @param string $template
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @param Collection|FilterConfig[] $filters
     * @return $this
     */
    public function setFilters($filters)
    {
        $this->filters = Collection::make($filters);
        return $this;
    }

    public function getFilters()
    {
        if (null === $this->filters) {
            $this->filters = new Collection();
        }
        return $this->filters;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param DataProvider $dataProvider
     * @return $this
     */
    public function setDataProvider(DataProvider $dataProvider)
    {
        $this->data_provider = $dataProvider;
        return $this;
    }

    /**
     * @return DataProvider
     */
    public function getDataProvider()
    {
        return $this->data_provider;
    }

    /**
     * @param FieldConfig[]|Collection $columns
     * @return $this
     */
    public function setColumns($columns)
    {
        $this->columns = Collection::make($columns);
        return $this;
    }

    /**
     * @return FieldConfig[]|Collection
     */
    public function getColumns()
    {
        if (null === $this->columns) {
            $this->columns = new Collection;
        }
        return $this->columns;
    }

    /**
     * @param $name
     * @return null|FieldConfig
     */
    public function getColumn($name)
    {
        foreach ($this->getColumns() as $column) {
            if ($column->getName() === $name) {
                return $column;
            }
        }

    }

    public function getCachingTime()
    {
        return $this->chaching_time;
    }

    public function setCachingTime($minutes)
    {
        $this->caching_time = $minutes;
        return $this;
    }

    public function addColumn(FieldConfig $column)
    {
        $this->columns->push($column);
        return $this;
    }

    /**
     * @param int $page_size
     * @return $this
     */
    public function setPageSize($page_size)
    {
        $this->page_size = (int)$page_size;
        return $this;
    }

    public function getPageSize()
    {
        return $this->page_size;
    }

} 