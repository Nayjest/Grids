<?php
/**
 * Created by PhpStorm.
 * User: Nayjest
 * Date: 15.02.14
 * Time: 2:13
 */

namespace Nayjest\DataView;

$gridConfig = [
    'fields' => [
        'name' => [
            'label' => 'Name',
            'order' => 1
        ],
    ]
];

abstract class Field
{
    public $order;
    public $label;
    public $name;
    public $value;
    public $valueTemplate = null;
    public $labelTemplate = null;

    public function renderValue()
    {
        return $this->valueTemplate ? \View::make($this->valueTemplate, ['field' => $this]) : $this->value;
    }

    public function renderLabel()
    {
        return $this->valueTemplate ? \View::make($this->$labelTemplate, ['field' => $this]) : $this->label;
    }
}


class DataView
{
    public $id;
    public $fields = [];
    public $dataProvider;
    public $template;
    public $pageSize = null;
    public $currentPage = 1;
    public $rows;
    public $filterControls;
    public $route;

    protected function applyUserFilters()
    {
        foreach ($this->filterControls as $filter) {
            $filter->isActive() and $this->dataProvider->addFilter($filter->filter);
        }
    }

    public function render()
    {
        $this->applyUserFilters();
        $offset = $this->pageSize ? (($this->currentPage - 1) * $this->pageSize) : 0;
        $rows = $this->dataProvider->fetch($offset, $this->pageSize);
        return \View::make($this->template, $rows);
    }

    public function getPageCount()
    {
        if (!$this->pageSize) {
            return 1;
        }
        return ceil($this->dataProvider->getRowsCount() / $this->pageSize);
    }
}


abstract class DataProvider
{
    public $filters = [];
    public $sorter;
    public $model;

    abstract public function fetch($offset, $limit);

    abstract public function getRowsCount();

    public function addFilter(Filter $filter)
    {
        $this->filters[] = $filter;
    }
}


class Grid extends DataView
{

}

class Column extends Field
{
    public $sorter;
    public $labelTemplate = 'data-view::column';

}

abstract class Sorter
{
    public $fieldName;
    public $order;
    const ORDER_ASC = 1;
    const ORDER_DESC = 2;
    const ORDER_NONE = null;

    abstract public function sort($src);
}


abstract class Filter
{
    abstract public function filter($src);
}

abstract class UserFilter extends Filter
{
    public $value;
}

class FilterControl
{
    public $template;

    public $filter;

    public $value;

    public function isActive()
    {
        return !empty($this->value);
    }

    public function render()
    {
        \View::make($this->template, ['filter' => $this]);
    }
}

class ArrayDataProvider extends DataProvider
{

    protected $src;

    public function __construct(array $src = [])
    {
        // keys of rows are ignored
        $this->src = array_values($src);
    }

    /**
     * @param int $offset
     * @param int $limit
     * @return \Generator
     */
    public function fetch($offset = 0, $limit = null)
    {
        $src = $this->src;
        if ($this->sorter !== null) {
            $this->sorter->sort($src);
        }
        /** @var $filter Filter */
        foreach ($this->filters as $filter) {
            $filter->filter($src);
        }
        return array_slice($src, $offset, $limit ? : null);
    }

    /**
     * @return int
     */
    public function getRowsCount()
    {
        return count($this->src);
    }

}

class Builder
{
    const CLASS_FIELD = '@class';
    public $defaultClass;
    public $src;
    public $children = [
        'dataProvider' => 'DataProvider',
        'fields' => ['Field'],
        'filterControls' => ['FilterControl']
    ];
    public $mapping = [
        'src' => 'dataProvider',
        'filters' => 'filterControls',
        'columns' => 'fields'
    ];
    public $defaults = [

    ];
    public $flags = [
        'colorPicker' => ['template', 'data-view::inputs.color-picker']
    ];
    public $parent;


    protected function initialize($instance)
    {

    }

    public function __construct($src, $class = null)
    {
        $class and ($this->defaultClass = $class);
        $this->src = $src;
    }

    protected function get($key, $default = null)
    {
        return isset($this->src[$key]) ? $this->src[$key] : $default;
    }

    public function make()
    {
        $instance = $this->makeInstance();
        $publicProperties = get_object_vars($instance);
        foreach (array_intersect_key($this->src, $publicProperties) as $key => $value) {
            $instance->{$key} = $value;
            unset($this->src[$key]);
        }
        $this->initialize($instance);
        return $instance;
    }


    public function getClass()
    {
        return $this->get(static::CLASS_FIELD, $this->defaultClass);
    }

    public function getConstructorArguments()
    {
        return [];
    }

    protected function makeInstance()
    {
        $class = $this->getClass();
        $arguments = $this->getConstructorArguments();
        switch (count($arguments)) {
            case 0:
                return new $class();
            case 1:
                return new $class(array_shift($arguments));
            case 2:
                return new $class(array_shift($arguments), array_shift($arguments));
            case 3:
                return new $class(array_shift($arguments), array_shift($arguments), array_shift($arguments));
            default:
                $reflection = new \ReflectionClass($class);
                return $reflection->newInstanceArgs($arguments);
        }

    }
}

# @outdated
class DataProviderBuilder extends Builder
{
    public function getClass()
    {
        if ($this->get('table')) return 'DqlDataProvider';
        if ($this->get('model')) return 'EloquentlDataProvider';
    }
}

class Facade
{
    public function create($config)
    {
        $builder = new Builder($config, 'DataSet');
        return $builder->make();
    }
    public function make($config)
    {
        return $this->create($config)->render();
    }
}

class BuilderBlueprints {
    public function dataProvider() {
        return [
            'getClass' => function($src) {
                    if ($this->get('table')) return 'DqlDataProvider';
                    if ($this->get('model')) return 'EloquentlDataProvider';
                }
        ];
    }
}