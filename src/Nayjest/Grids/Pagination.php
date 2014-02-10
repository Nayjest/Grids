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
    public $enabled = true;
    public $pageSize = null;
    public $currentPage = 1;

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
} 