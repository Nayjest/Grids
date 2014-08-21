<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 27.05.14
 * Time: 16:18
 */

namespace Nayjest\Grids;

abstract class DataProvider {

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

    abstract public function orderBy($field_name, $direction);

    abstract public function filter($field_name, $operator, $value);

    abstract public function getCollection();

    /**
     * @return \Illuminate\Pagination\Paginator
     */
    abstract public function getPaginator();

    abstract public function getRow();
//    {
//        if ($this->index < $this->count()) {
//            $this->index++;
//            return new DataRow(next($this->src));
//        } else {
//            return null;
//        }
//    }

    /**
     * @return int
     */
    abstract public function count();
//    {
//        return count($this->src);
//    }
} 