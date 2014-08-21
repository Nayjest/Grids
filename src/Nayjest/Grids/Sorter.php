<?php
namespace Nayjest\Grids;

use Form;
use Request;

class Sorter
{
    /**
     * @var Grid
     */
    protected $grid;

    public function __construct(Grid $grid)
    {
        $this->grid = $grid;
    }

    public function link(FieldConfig $column, $direction)
    {
        return (new GridInputProcessor($this->grid))
            ->setSorting($column, $direction)
            ->getUrl();
    }

    protected function sortBy($field, $direction)
    {
        foreach ($this->grid->getConfig()->getColumns() as $column) {
            if ($column->getName() === $field) {
                $column->setSorting($direction);
            } else {
                $column->setSorting(null);
            }
        }

        $this->grid->getConfig()->getDataProvider()->orderBy($field, $direction);
    }

    public function apply()
    {
        $input = $this->grid->getInputProcessor()->getInput();
        //var_dump($input);die();
        if (isset($input['sort'])) {
            foreach ($input['sort'] as $field => $direction) {
                $this->sortBy($field, $direction);
            }
        }
    }
} 