<?php
namespace Nayjest\Grids;

use Illuminate\Support\Collection;
use Nayjest\Grids\Components\Base\IRenderableComponent;
use Nayjest\Grids\Components\Base\TComponent;
use Nayjest\Grids\Components\Base\TRegistry;
use Nayjest\Grids\Components\Base\IRegistry;
use Nayjest\Grids\Components\TFoot;
use Nayjest\Grids\Components\THead;
use Nayjest\Grids\Components\Tr;

class GridConfig implements IRegistry
{
    use TRegistry;
    use TComponent;

    const SECTION_DO_NOT_RENDER = 'not_render';

    protected $template = 'grids::default';

    /** @var FieldConfig[]|Collection */
    protected $columns = null;

    /** @var  DataProvider $data_provider */
    protected $data_provider;

    protected $page_size = 50;

    /** @var Collection|FilterConfig[] $filters */
    protected $filters;

    /** @var int */
    protected $caching_time = 0;

    protected $main_template = '*.grid';

    protected $row_component;

    /**
     * @return IRenderableComponent
     */
    public function getRowComponent()
    {
        if (!$this->row_component) {
            $this->row_component = (new Tr)
                ->setRenderSection(self::SECTION_DO_NOT_RENDER);
            $this->addComponent($this->row_component);
        }
        return $this->row_component;
    }

    /**
     * @param mixed $row_component
     */
    public function setRowComponent(IRenderableComponent $row_component)
    {
        $this->row_component = $row_component;
        $this->addComponent($row_component);
        $row_component->setRenderSection(self::SECTION_DO_NOT_RENDER);
        return $this;
    }

    /**
     * Components in registry by default
     *
     * @return array
     */
    protected function getDefaultComponents()
    {
        return [
            new THead,
            new TFoot
        ];
    }

    /**
     * @param string $template
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    public function setMainTemplate($template)
    {
        $this->main_template = $template;
        return $this;
    }

    public function getMainTemplate()
    {
        return str_replace('*.', "$this->template.", $this->main_template);
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
        return $this->caching_time;
    }

    public function setCachingTime($minutes)
    {
        $this->caching_time = $minutes;
        return $this;
    }

    public function addColumn(FieldConfig $column)
    {
        if ($this->columns === null) {
            $this->setColumns([]);
        }
        $this->columns->push($column);
        return $this;
    }

    /**
     * @param int $pageSize
     * @return $this
     */
    public function setPageSize($pageSize)
    {
        $this->page_size = (int)$pageSize;
        return $this;
    }

    public function getPageSize()
    {
        return $this->page_size;
    }

} 