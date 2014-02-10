<?php
/**
 * Created by PhpStorm.
 * User: Nayjest
 * Date: 08.02.14
 * Time: 18:31
 */

namespace Nayjest\Grids;


class Pagination
{
    protected $enabled = true;
    protected $pageSize = null;
    protected $currentPage = 1;

    public function __construct($pageSize = null, $currentPage = 1, $enabled = true)
    {
        $this->setPageSize($pageSize);
        $this->setCurrentPage($currentPage);
        $this->setEnabled($enabled);
    }

    public function getPageCount($records)
    {
        if (!$this->pageSize or !$this->enabled) {
            return 1;
        }
        return ceil($records / $this->pageSize);
    }

    public function getOffset()
    {
        return ($this->currentPage - 1) * $this->pageSize;
    }

    public function getPageSize()
    {
        return $this->pageSize;
    }

    public function setPageSize($size)
    {
        $this->pageSize = $size;
    }

    public function setCurrentPage($currentPageNumber)
    {
        $this->currentPage = $currentPageNumber;
    }

    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    public function setEnabled($flag)
    {
        $this->enabled = $flag;
    }

    public function isEnabled()
    {
        return $this->enabled;

    }
} 