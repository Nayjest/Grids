<?php
namespace Nayjest\Grids;

use View;

class Filter
{
    /** @var FilterConfig */
    protected $config;

    /** @var FieldConfig */
    protected $column;

    /**
     * Constructor.
     *
     * @param FilterConfig $config
     * @param FieldConfig $column
     * @param Grid $grid
     */
    public function __construct(
        FilterConfig $config,
        FieldConfig $column,
        Grid $grid
    )
    {
        $this->config = $config;
        $this->column = $column;
        $this->grid = $grid;
    }

    /**
     * Returns input name for the filter.
     *
     * @return string
     */
    public function getInputName()
    {
        $key = $this->grid->getInputProcessor()->getKey();
        $name = $this->config->getId();
        return "{$key}[filters][{$name}]";
    }

    /**
     * Returns filter configuration.
     *
     * @return FilterConfig
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Returns filters value.
     *
     * @return mixed
     */
    public function getValue()
    {
        $from_input = $this
            ->grid
            ->getInputProcessor()
            ->getFilterValue($this->config->getId());
        if ($from_input === null) {
            return $this->config->getDefaultValue();
        } else {
            return $from_input;
        }
    }

    /**
     * Renders filtering control.
     *
     * @return string
     */
    public function render()
    {
        $data = $this->grid->getViewData();
        $data['column'] = $this->column;
        $data['filter'] = $this;
        $data['label'] = $this->config->getLabel();
        return View::make(
            $this->getTemplate(),
            $data
        )->render();
    }

    /**
     * Returns name of template for filtering control.
     *
     * @return string
     */
    protected function getTemplate()
    {
        $filter_tpl = $this->config->getTemplate();
        $grid_tpl = $this->grid->getConfig()->getTemplate();
        return str_replace('*.', "$grid_tpl.filters.", $filter_tpl);
    }

    /**
     * Applies filtering to data source.
     */
    public function apply()
    {
        $value = $this->getValue();
        if (null === $value || '' === $value) {
            return;
        }
        if ($func = $this->config->getFilteringFunc()) {
            $func($value, $this->grid->getConfig()->getDataProvider());
            return;
        }
        $isLike = $this->config->getOperator() === FilterConfig::OPERATOR_LIKE;
        if ($isLike && strpos($value, '%') === false) {
            $value = "%$value%";
        }
        $this->grid->getConfig()->getDataProvider()->filter(
            $this->config->getName(),
            $this->config->getOperator(),
            $value
        );
    }
}
