<?php
namespace Nayjest\Grids;

use \View;

class Grid extends AbstractDataSetRenderer //implements GridInterface
{
    public $section;
    public $view = 'grids::grid';
    //protected function

    protected $columns = [];

    public function getView()
    {
        return $this->view;
    }

    public function setView($view)
    {
        $this->view = $view;
    }

    public function setColumns($columns)
    {
        $this->columns = $columns;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function getData()
    {
        return [
            'grid' => $this, #@todo remove this, temporary
            'rows' => $this->fetch(),
            'columns' => $this->getColumns(),
            'pagination' => $this->getPagination()
        ];
    }

    public function render()
    {
        return View::make(
            $this->view, $this->getData()
        );
    }
} 