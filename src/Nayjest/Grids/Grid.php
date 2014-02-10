<?php
namespace Nayjest\Grids;
use \View;

class Grid extends AbstractDataSetRenderer //implements GridInterface
{
    protected $view = 'grids::grid';
    //protected function

    public $columns = [];

    public function render()
    {
        return View::make(
            $this->view, ['grid' => $this]
        );
    }
} 