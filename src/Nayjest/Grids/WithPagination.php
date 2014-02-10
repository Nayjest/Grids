<?php
namespace Nayjest\Grids;


trait WithPagination
{
    /**
     * @var Pagination
     */
    protected $pagination;

    /**
     * @param Pagination $pagination
     */
    public function setPagination(Pagination $pagination)
    {
        $this->pagination = $pagination;
    }

    /**
     * @return Pagination
     */
    public function getPagination()
    {
        return $this->pagination;
    }
} 