<?php
namespace Nayjest\Grids\Components;

use DomainException;
use Illuminate\Foundation\Application;
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

    public function __construct()
    {
        if (version_compare(Application::VERSION, '5', '>')) {
            $className = get_class($this);
            throw new DomainException(
                "$className designed for usage only with Laravel 4.X"
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $result = (string)$this->links();
        return $result;
    }

    protected function setupPaginationForReading()
    {
        $this->pagination_factory->setPageName("$this->input_key.page");
    }

    protected function setupPaginationForLinks()
    {
        $this->pagination_factory->setPageName("{$this->input_key}[page]");
    }

    protected function restorePaginationOptions()
    {
        $this->pagination_factory->setPageName($this->previous_page_name);
    }

    protected function links()
    {

        $this->setupPaginationForReading();
        /** @var  Paginator $paginator */
        $paginator = $this->grid->getConfig()
            ->getDataProvider()
            ->getPaginator();

        $this->setupPaginationForLinks();
        $input = $this->grid->getInputProcessor()->getInput();
        if (isset($input['page'])) {
            unset($input['page']);
        }
        $res = (string)$paginator->appends($this->input_key, $input)->links();
        $this->restorePaginationOptions();
        return $res;
    }

    public function initialize(Grid $grid)
    {
        parent::initialize($grid);
        $this->pagination_factory = $grid
            ->getConfig()
            ->getDataProvider()
            ->getPaginationFactory();
        $this->previous_page_name = $this->pagination_factory->getPageName();
        $this->input_key = $grid->getInputProcessor()->getKey();
        $this->setupPaginationForReading();
    }
}
