<?php
namespace Nayjest\Grids;

use Input;

/**
 * Class DataProvider
 * @package Nayjest\Grids
 */
abstract class DataProvider
{
    const EVENT_FETCH_ROW = 'grid.dp.fetch_row';

    protected $src;

    protected $index = 0;

    protected $page_size = 100;

    public function __construct($src)
    {
        $this->src = $src;
    }

    /**
     * @return $this
     */
    public function reset()
    {
        reset($this->src);
        return $this;
    }

    /**
     * @param int $pageSize
     * @return $this
     */
    public function setPageSize($pageSize)
    {
        $this->page_size = $pageSize;
        return $this;
    }

    /**
     * @todo support for multiple pagination
     */
    public function getCurrentPage()
    {
        return $this->getPaginator()->getCurrentPage();

    }

    /**
     * @return int row id starting from 1, considering pagination
     */
    protected function getRowId()
    {
        $offset = ($this->getCurrentPage() - 1) * $this->page_size;
        return $offset + $this->index;
    }

    /**
     * Sets data sorting
     *
     * @param string $fieldName
     * @param $direction
     */
    abstract public function orderBy($fieldName, $direction);

    abstract public function filter($fieldName, $operator, $value);

    abstract public function getCollection();

    /**
     * @return \Illuminate\Pagination\Paginator
     */
    abstract public function getPaginator();

    /**
     * @return \Illuminate\Pagination\Factory
     */
    abstract public function getPaginationFactory();

    /** @return DataRow|null */
    abstract public function getRow();

    /**
     * @return int
     */
    abstract public function count();
} 