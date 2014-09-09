<?php
namespace Nayjest\Grids\Components;

use Nayjest\Grids\Components\Base\IRenderableComponent;
use Nayjest\Grids\Components\Base\TComponent;
use Nayjest\Grids\Components\Base\TComponentView;
use Nayjest\Grids\ArrayDataRow;
use Nayjest\Grids\DataProvider;
use Nayjest\Grids\DataRow;
use Nayjest\Grids\FieldConfig;
use Nayjest\Grids\IdFieldConfig;
use Nayjest\Grids\Grid;
use Illuminate\Support\Facades\Event;

class TotalsRow extends ArrayDataRow implements IRenderableComponent
{
    use TComponent {
        TComponent::initialize as protected initializeComponent;
    }
    use TComponentView;

    /** @var \Illuminate\Support\Collection|FieldConfig[] */
    protected $fields;

    protected $field_names;

    public function __construct(array $field_names = [])
    {
        $this->template = '*.components.totals';
        $this->name = 'totals';

        $this->field_names = $field_names;
        $this->id = 'Totals';
        $this->src = [];
        foreach ($this->field_names as $name) {
            $this->src[$name] = 0;
        }

    }

    protected function provideFields()
    {
        $field_names = $this->field_names;
        $this->fields = $this->grid->getConfig()->getColumns()->filter(
            function (FieldConfig $field) use ($field_names) {
                return in_array($field->getName(), $field_names);
            }
        );
    }

    protected function listen(DataProvider $dp)
    {
        Event::listen(
            DataProvider::EVENT_FETCH_ROW,
            function (DataRow $row, DataProvider $provider) use ($dp) {
                if ($dp !== $provider) return;
                foreach ($this->fields as $field) {
                    $this->src[$field->getName()] += $row->getCellValue($field);
                }
            }
        );
    }

    public function initialize(Grid $grid)
    {
        $this->initializeComponent($grid);
        $this->provideFields();
        $this->listen(
            $this->grid->getConfig()->getDataProvider()
        );
    }

    /**
     * @param FieldConfig $field
     * @return bool
     */
    public function uses(FieldConfig $field)
    {
        return in_array($field, $this->fields->toArray()) or $field instanceof IdFieldConfig;
    }

    public function getCellValue($field)
    {
        if (!$field instanceof FieldConfig) {
            $field = $this->grid->getConfig()->getColumn($field);
        }
        if ($this->uses($field) and !$field instanceof IdFieldConfig) {
            return parent::getCellValue($field);
        } else {
            return null;
        }
    }


}