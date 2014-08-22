<?php
namespace Nayjest\Grids;

use Event;
use View;

class Grid
{

    const SORT_ASC = 'ASC';
    const SORT_DESC = 'DESC';

    const EVENT_PREPARE  = 'grid.prepare';

    /** @var GridConfig */
    protected $config;

    /** @var bool  */
    protected $prepared = false;

    /** @var  Sorter */
    protected $sorter;

    /** @var  GridInputProcessor */
    protected $input_processor;

    protected $filtering;

    public function __construct(GridConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return string
     */
    protected function getMainTemplate()
    {
        return $this->config->getTemplate() . '.grid';
    }

    protected function prepare()
    {
        if ($this->prepared === true) {
            return;
        }
        $cfg = $this->config;

        if ($cfg->getName() === null) {
            $this->provideName();
        }

        $cfg->getDataProvider()
            ->setPageSize($cfg->getPageSize());
        //$this->getInputProcessor()->applyChanges();
        $this->getFiltering()->apply();
        $this->prepareColumns();
        $this->getSorter()->apply();
        Event::fire(self::EVENT_PREPARE, $this);
        $this->prepared = true;
    }

    protected function prepareColumns()
    {
        if ($this->needToSortColumns()) {
            $this->sortColumns();
        }
    }

    /**
     * Provides unique name for each grid on the page
     */
    protected function provideName()
    {
        $bt_len = 10;
        $bt = debug_backtrace(null, $bt_len);
        $str = '';
        for ($id = 2; $id < $bt_len; $id++) {
            $trace = isset($bt[$id]) ? $bt[$id] : [];
            if (empty($trace['class']) or !$this instanceof $trace['class']) {
                # may be closure
                if (isset($trace['file']) and isset($trace['line'])) {
                    $str .= $trace['file'] . $trace['line'];
                }
            }
        }
        $this->config->setName(substr(md5($str), 0, 16));
    }

    /**
     * @return bool
     */
    protected function needToSortColumns()
    {
        foreach ($this->config->getColumns() as $column) {
            if ($column->getOrder() !== 0) {
                return true;
            }
        }
        return false;
    }

    protected function sortColumns()
    {
        $this->config->getColumns()->sort(function (FieldConfig $a, FieldConfig $b) {
            return $a->getOrder() > $b->getOrder();
        });
    }

    /**
     * @return Sorter
     */
    public function getSorter()
    {
        if (null === $this->sorter) {
            $this->sorter = new Sorter($this);
        }
        return $this->sorter;
    }

    /**
     * @return GridInputProcessor
     */
    public function getInputProcessor()
    {
        if (null === $this->input_processor) {
            $this->input_processor = new GridInputProcessor($this);
        }
        return $this->input_processor;
    }

    /**
     * @return GridConfig
     */
    public function getConfig()
    {
        return $this->config;
    }

    public function getViewData()
    {
        return [
            'grid' => $this,
            'data' => $this->config->getDataProvider(),
            'template' => $this->config->getTemplate(),
            'columns' => $this->config->getColumns()
        ];
    }

    /**
     * @return View
     */
    public function render()
    {
        $this->prepare();
        $this->config->getDataProvider()->reset();
        return View::make(
            $this->getMainTemplate(),
            $this->getViewData()
        );
    }

    public function getFiltering()
    {
        if ($this->filtering === null) {
            $this->filtering = new Filtering($this);
        }
        return $this->filtering;
    }

    public function renderFilter(FilterConfig $filter)
    {
        $data = $this->getViewData();
        $data['column'] = $filter->getAttachedColumn();
        $data['filter'] = $filter;
        return View::make(
            $this->getFilterTemplate($filter),
            $data
        );
    }

    public function links()
    {
        return $this->config
            ->getDataProvider()
            ->getPaginator()
            ->appends(
                $this->getInputProcessor()->getKey(),
                $this->getInputProcessor()->getInput()
            )
            ->links();
    }

    public function hasActionsColumn()
    {
        return $this->getFiltering()->available();
    }

    public function __toString()
    {
        return (string)$this->render();
    }

} 