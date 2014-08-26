<?php
namespace Nayjest\Grids;

use Input;

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
     * @param int $page_size
     * @return $this
     */
    public function setPageSize($page_size)
    {
        $this->page_size = $page_size;
        return $this;
    }

    /**
     * @todo support for multiple pagination
     */
    public function getCurrentPage()
    {
        return (int)Input::get('page', 1) ? : 1;

    }

    protected function getRowId()
    {
        $offset = ($this->getCurrentPage() - 1) * $this->page_size;
        return $offset + $this->index;
    }

    abstract public function orderBy($field_name, $direction);

    abstract public function filter($field_name, $operator, $value);

    abstract public function getCollection();

    /**
     * @return \Illuminate\Pagination\Paginator
     */
    abstract public function getPaginator();

    /** @return DataRow|null */
    abstract public function getRow();

    /**
     * @return int
     */
    abstract public function count();
} 