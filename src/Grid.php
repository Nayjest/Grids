<?php
namespace Nayjest\Grids;

use Event;
use Cache;
use Nayjest\Grids\Components\TFoot;
use Nayjest\Grids\Components\THead;
use View;

class Grid
{

    const SORT_ASC = 'ASC';
    const SORT_DESC = 'DESC';

    const EVENT_PREPARE = 'grid.prepare';
    const EVENT_CREATE = 'grid.create';

    /** @var GridConfig */
    protected $config;

    /** @var bool */
    protected $prepared = false;

    /** @var  Sorter */
    protected $sorter;

    /** @var  GridInputProcessor */
    protected $input_processor;

    protected $filtering;

    public function __construct(GridConfig $config)
    {
        $this->config = $config;
        if ($config->getName() === null) {
            $this->provideName();
        }

        $this->initializeComponents();
        Event::dispatch(self::EVENT_CREATE, $this);
    }

    /**
     * @return string
     */
    protected function getMainTemplate()
    {
        return $this->config->getMainTemplate();
    }


    public function prepare()
    {
        if ($this->prepared === true) {
            return;
        }
        $cfg = $this->config;
        $cfg->getDataProvider()
            ->setPageSize(
                $cfg->getPageSize()
            )
            ->setCurrentPage(
                $this->getInputProcessor()->getValue('page', 1)
            );
        $this->getConfig()->prepare();
        $this->getFiltering()->apply();
        $this->prepareColumns();
        $this->getSorter()->apply();
        Event::dispatch(self::EVENT_PREPARE, $this);
        $this->prepared = true;
    }

    protected function initializeComponents()
    {
        $this->getConfig()->initialize($this);
    }

    protected function prepareColumns()
    {
        if ($this->needToSortColumns()) {
            $this->sortColumns();
        }
    }

    /**
     * Provides unique name for each grid on the page
     *
     * @return null
     */
    protected function provideName()
    {
        $bt_len = 10;
        $backtrace = debug_backtrace(null, $bt_len);
        $str = '';
        for ($id = 2; $id < $bt_len; $id++) {
            $trace = isset($backtrace[$id]) ? $backtrace[$id] : [];
            if (empty($trace['class']) || !$this instanceof $trace['class']) {
                # may be closure
                if (isset($trace['file'], $trace['line'])) {
                    $str .= $trace['file'] . $trace['line'];
                }
            }
        }
        $this->config->setName(substr(md5($str), 0, 16));
    }

    /**
     * Returns true if columns must be sorted.
     *
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

    /**
     * Sorts columns according to its order.
     */
    protected function sortColumns()
    {
        $this->config->getColumns()->sort(function (FieldConfig $a, FieldConfig $b) {
            return $a->getOrder() > $b->getOrder();
        });
    }

    /**
     * Returns data sorting manager.
     *
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
     * Returns instance of GridInputProcessor.
     *
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
     * Renders grid.
     *
     * @return View|string
     */
    public function render()
    {
        $key = $this->getInputProcessor()->getUniqueRequestId();
        $caching_time = $this->config->getCachingTime();
        if ($caching_time && ($output = Cache::get($key))) {
            return $output;
        } else {
            $this->prepare();
            $provider = $this->config->getDataProvider();
            $provider->reset();
            $output = View::make(
                $this->getMainTemplate(),
                $this->getViewData()
            )->render();
            if ($caching_time) {
                Cache::put($key, $output, $caching_time);
            }
            return $output;
        }
    }

    /**
     * Returns footer component.
     *
     * @return TFoot|null
     */
    public function footer()
    {
        return $this->getConfig()->getComponentByName('tfoot');
    }

    /**
     * Returns header component.
     *
     * @return THead|null
     */
    public function header()
    {
        return $this->getConfig()->getComponentByName('thead');
    }

    /**
     * Returns data filtering manager.
     *
     * @return Filtering
     */
    public function getFiltering()
    {
        if ($this->filtering === null) {
            $this->filtering = new Filtering($this);
        }
        return $this->filtering;
    }

    /**
     * Renders grid object when it is treated like a string.
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->render();
    }
}
