<?php
namespace Nayjest\Grids\Components;

use Nayjest\Grids\Components\Base\RenderableComponent;

/**
 * Class RecordsPerPage
 *
 * The component renders control
 * for switching count of records displayed per page.
 *
 * @package Nayjest\Grids\Components
 */
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

    /**
     * Returns variants.
     *
     * @return array|int[]
     */
    public function getVariants()
    {
        return array_combine(array_values($this->variants),array_values($this->variants));
    }

    /**
     * Sets variants.
     *
     * @param array|int[] $variants
     * @return $this
     */
    public function setVariants(array $variants)
    {
        $this->variants = $variants;
        return $this;
    }

    /**
     * Returns name of related input.
     *
     * @return string
     */
    public function getInputName()
    {
        $key = $this->grid->getInputProcessor()->getKey();
        return "{$key}[filters][records_per_page]";
    }

    /**
     * Returns current value from input.
     * Default grids pre-configured page size will be returned if there is no input.
     *
     * @return int|null
     */
    public function getValue()
    {
        $from_input = $this
            ->grid
            ->getInputProcessor()
            ->getFilterValue('records_per_page');
        if ($from_input === null) {
            return $this->grid->getConfig()->getPageSize();
        } else {
            return (int) $from_input;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function prepare()
    {
        $value = $this->getValue();
        if (!$value || !is_numeric($value)) return;
        $this->grid->getConfig()->getDataProvider()->setPageSize($value);
    }
}
