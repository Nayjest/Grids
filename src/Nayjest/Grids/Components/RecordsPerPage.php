<?php
namespace Nayjest\Grids\Components;


use Nayjest\Grids\Components\Base\RenderableComponent;

class RecordsPerPage extends RenderableComponent
{

    protected $name = 'records_per_page';

    protected $variants = [
        50,
        100,
        300,
        1000
    ];

    protected $template = '*.components.records_per_page';

    public function getVariants()
    {
        return array_combine(array_values($this->variants),array_values($this->variants));
    }

    public function setVariants(array $variants)
    {
        $this->variants = $variants;
        return $this;
    }

    public function getInputName()
    {
        $key = $this->grid->getInputProcessor()->getKey();
        return "{$key}[filters][records_per_page]";
    }

    public function getValue()
    {
        $from_input = $this
            ->grid
            ->getInputProcessor()
            ->getFilterValue('records_per_page');
        if ($from_input === null) {
            return 50;
        } else {
            return $from_input;
        }
    }

    public function prepare()
    {
        $value = $this->getValue();
        if (!$value or !is_numeric($value)) return;
        $this->grid->getConfig()->getDataProvider()->setPageSize($value);
    }

} 