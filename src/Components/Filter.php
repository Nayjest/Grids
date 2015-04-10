<?php
namespace Nayjest\Grids\Components;


use Nayjest\Grids\Components\Base\RenderableComponent;

class Filter extends RenderableComponent
{
    protected $filtering_func;

    protected $default_value;

    protected $template = '*.components.filters.input';

    protected $label;

    /**
     * @return callable
     */
    public function getFilteringFunc()
    {
        return $this->filtering_func;
    }

    /**
     * @param callable $func
     * @return $this
     */
    public function setFilteringFunc($func)
    {
        $this->filtering_func = $func;
        return $this;
    }

    public function getInputName()
    {
        $key = $this->grid->getInputProcessor()->getKey();
        return "{$key}[filters][{$this->name}]";
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    public function getDefaultValue()
    {
        return $this->default_value;
    }

    public function setDefaultValue($value)
    {
        $this->default_value = $value;
        return $this;
    }

    public function getValue()
    {
        $from_input = $this
            ->grid
            ->getInputProcessor()
            ->getFilterValue($this->name);
        if ($from_input === null) {
            return $this->getDefaultValue();
        } else {
            return $from_input;
        }
    }

    protected function hasValue()
    {
        return $this->getValue() !== null && $this->getValue() !== '';
    }

    public function prepare()
    {
        if (!$this->hasValue()) {
            return;
        }
        $value = $this->getValue();
        if ($func = $this->getFilteringFunc()) {
            $func($value, $this->grid->getConfig()->getDataProvider());
            return;
        }
    }
}
