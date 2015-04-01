<?php
namespace Nayjest\Grids\Components;

use Nayjest\Grids\Components\Base\RenderableComponentInterface;
use Nayjest\Grids\Components\Base\TComponent;
use Nayjest\Grids\Components\Base\TComponentView;
use Nayjest\Grids\ArrayDataRow;
use Nayjest\Grids\DataProvider;
use Nayjest\Grids\DataRow;
use Nayjest\Grids\FieldConfig;
use Nayjest\Grids\IdFieldConfig;
use Nayjest\Grids\Grid;
use Illuminate\Support\Facades\Event;
use Exception;

/**
 * Class TotalsRow
 *
 * The component renders row with totals for current page.
 *
 * @package Nayjest\Grids\Components
 */
class TotalsRow extends ArrayDataRow implements RenderableComponentInterface
{
    use TComponent {
        TComponent::initialize as protected initializeComponent;
    }
    use TComponentView;

    const OPERTATION_SUM = 'sum';
    const OPERATION_AVG = 'avg';
    const OPERATION_COUNT = 'count';
    //const OPERATION_MAX = 'max';
    //const OPERATION_MIN = 'min';

    /** @var \Illuminate\Support\Collection|FieldConfig[] */
    protected $fields;

    protected $field_names;

    protected $field_operations = [];

    protected $rows_processed = 0;

    /**
     * @param array|string[] $fieldNames
     */
    public function __construct(array $fieldNames = [])
    {
        $this->template = '*.components.totals';
        $this->name = 'totals';

        $this->field_names = $fieldNames;
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

    protected function listen(DataProvider $provider)
    {
        Event::listen(
            DataProvider::EVENT_FETCH_ROW,
            function (DataRow $row, DataProvider $currentProvider) use ($provider) {
                if ($currentProvider !== $provider) return;
                $this->rows_processed++;
                foreach ($this->fields as $field) {
                    $name = $field->getName();
                    $operation = $this->getFieldOperation($name);
                    switch($operation) {

                        case self::OPERTATION_SUM:
                            $this->src[$name] += $row->getCellValue($field);
                            break;
                        case self::OPERATION_COUNT:
                            $this->src[$name] = $this->rows_processed;
                            break;
                        case self::OPERATION_AVG:
                            if (empty($this->src["{$name}_sum"])) {
                                $this->src["{$name}_sum"] = 0;
                            }
                            $this->src["{$name}_sum"] += $row->getCellValue($field);
                            $this->src[$name] = round(
                                $this->src["{$name}_sum"] / $this->rows_processed,
                                2
                            );
                            break;
                        default:
                            throw new Exception("TotalsRow:Unknown aggregation operation.");
                    }

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
        return in_array($field, $this->fields->toArray()) || $field instanceof IdFieldConfig;
    }

    public function getCellValue($field)
    {
        if (!$field instanceof FieldConfig) {
            $field = $this->grid->getConfig()->getColumn($field);
        }
        if ($this->uses($field) && !$field instanceof IdFieldConfig) {
            return parent::getCellValue($field);
        } else {
            return null;
        }
    }

    /**
     * @return int
     */
    public function getRowsProcessed()
    {
        return $this->rows_processed;
    }

    /**
     * @param array $fieldNames
     * @return $this
     */
    public function setFieldNames(array $fieldNames)
    {
        $this->field_names = $fieldNames;
        return $this;
    }

    /**
     * @return array
     */
    public function getFieldNames()
    {
        return $this->field_names;
    }

    /**
     * @param array $fieldOperations
     * @return $this
     */
    public function setFieldOperations(array $fieldOperations)
    {
        $this->field_operations = $fieldOperations;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getFieldOperations()
    {
        return $this->field_operations;
    }


    /**
     * @param string $fieldName
     * @return string
     */
    public function getFieldOperation($fieldName)
    {
        return isset($this->field_operations[$fieldName])?$this->field_operations[$fieldName]:self::OPERTATION_SUM;
    }


}