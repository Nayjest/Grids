<?php
namespace Nayjest\Grids\Components;

use Illuminate\Pagination\Paginator;
use Nayjest\Grids\Components\Base\RenderableComponent;
use Nayjest\Grids\Grid;

class Pager extends RenderableComponent
{

    /**
     * @var \Illuminate\Pagination\Factory
     */
    protected $pagination_factory;

    protected $input_key;

    protected $previous_page_name;

    public function render() {
        $this->setupPaginationForLinks();
        $result = (string)$this->links();
        $this->restorePaginationOptions();
        return $result;
    }

    protected function setupPaginationForReading()
    {
        $this->pagination_factory->setPageName("$this->input_key.page");
    }
    protected function setupPaginationForLinks()
    {
        $this->previous_page_name = $this->pagination_factory->getPageName();
        $this->pagination_factory->setPageName("{$this->input_key}[page]");
    }

    protected function restorePaginationOptions()
    {
        $this->pagination_factory->setPageName($this->previous_page_name);
    }

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
        return $paginator->appends($this->input_key, $input)->links();
    }

    public function initialize(Grid $grid)
    {
        parent::initialize($grid);
        $this->pagination_factory = $grid
            ->getConfig()
            ->getDataProvider()
            ->getPaginationFactory();
        $this->input_key = $grid->getInputProcessor()->getKey();
        $this->setupPaginationForReading();
    }
}