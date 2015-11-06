<?php
namespace Nayjest\Grids\Components\Laravel5;

use Illuminate\Pagination\Paginator;
use Input;
use Nayjest\Grids\Components\Base\RenderableComponent;
use Nayjest\Grids\Grid;

class Pager extends RenderableComponent
{
    protected $input_key;

    protected $previous_page_name;

    protected $name = 'pager';

    public function render()
    {
        $this->setupPaginationForLinks();
        $result = (string)$this->links();
        return $result;
    }

    protected function setupPaginationForReading()
    {
        Paginator::currentPageResolver(function () {
            return Input::get("$this->input_key.page", 1);
        });
    }

    protected function setupPaginationForLinks()
    {
        /** @var  Paginator $paginator */
        $paginator = $this->grid->getConfig()
            ->getDataProvider()
            ->getPaginator();
        $paginator->setPageName("{$this->input_key}[page]");
    }

    /**
     * Renders pagination links & returns rendered html.
     */
    protected function links()
    {
        /** @var  Paginator $paginator */
        $paginator = $this->grid->getConfig()
            ->getDataProvider()
            ->getPaginator();
        $input = $this->grid->getInputProcessor()->getInput();
        if (isset($input['page'])) {
            unset($input['page']);
        }
        return str_replace('/?', '?',$paginator->appends($this->input_key, $input)->render());
    }

    public function initialize(Grid $grid)
    {
        parent::initialize($grid);
        $this->input_key = $grid->getInputProcessor()->getKey();
        $this->setupPaginationForReading();
    }
}
