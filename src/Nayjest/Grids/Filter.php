<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 20.08.2014
 * Time: 19:46
 */

namespace Nayjest\Grids;

use Nayjest\Grids\Components\Base\TComponent;
use Nayjest\Grids\Components\Base\TComponentView;
use View;

class Filter
{

    protected $config;

    protected $column;

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

    public function getInputName()
    {
        $key = $this->grid->getInputProcessor()->getKey();
        $name = $this->config->getId();
        return "{$key}[filters][{$name}]";
    }

    public function getConfig()
    {
        return $this->config;
    }

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

    public function render()
    {
        $data = $this->grid->getViewData();
        $data['column'] = $this->column;
        $data['filter'] = $this;
        $data['label'] = $this->config->getLabel();
        return View::make(
            $this->getTemplate(),
            $data
        );
    }


    protected function getTemplate()
    {
        $filter_tpl = $this->config->getTemplate();
        $grid_tpl = $this->grid->getConfig()->getTemplate();
        return str_replace('*.',"$grid_tpl.filters.", $filter_tpl);
    }

    public function apply()
    {
        $value = $this->getValue();
        if (null === $value or '' === $value) {
            return;
        }
        if ($func = $this->config->getFilteringFunc()) {
            $func($value, $this->grid->getConfig()->getDataProvider());
            return;
        }
        if($this->config->getOperator() === FilterConfig::OPERATOR_LIKE) {
            if(strpos($value,'%') === false) {
                $value = "%$value%";
            }
        }
        $this->grid->getConfig()->getDataProvider()->filter(
            $this->config->getName(),
            $this->config->getOperator(),
            $value
        );
    }

} 