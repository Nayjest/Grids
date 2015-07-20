<?php
namespace Nayjest\Grids\Components;

use LogicException;
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

    const OPERATION_SUM = 'sum';
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
     * Constructor.
     *
     * @param array|string[] $fieldNames
     */
    public function __construct(array $fieldNames = [])
    {
        $this->template = '*.components.totals';
        $this->name = 'totals';
        $this->setFieldNames($fieldNames);
        $this->id = 'Totals';
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

    /**
     * Creates listener for grid.dp.fetch_row event.
     *
     * The listener will perform totals calculation.
     *
     * @param DataProvider $provider
     */
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

                        case self::OPERATION_SUM:
                            $this->src[$name] += $row->getCellValue($field);
                            break;
                        case self::OPERATION_COUNT:
                            $this->src[$name] = $this->rows_processed;
                            break;
                        case self::OPERATION_AVG:
                            $key = "{$name}_sum";
                            if (empty($this->src[$key])) {
                                $this->src[$key] = 0;
                            }
                            $this->src[$key] += $row->getCellValue($field);
                            $this->src[$name] = round(
                                $this->src[$key] / $this->rows_processed,
                                2
                            );
                            break;
                        default:
                            throw new LogicException(
                                'TotalsRow: Unknown aggregation operation.'
                            );
                    }

                }

            }
        );
    }

    /**
     * Performs component initialization.
     *
     * @param Grid $grid
     * @return null
     */
    public function initialize(Grid $grid)
    {
        $this->initializeComponent($grid);
        $this->provideFields();
        $this->listen(
            $this->grid->getConfig()->getDataProvider()
        );
        return null;
    }

    /**
     * Returns true if the component uses specified column for totals calculation.
     *
     * @param FieldConfig $field
     * @return bool
     */
    public function uses(FieldConfig $field)
    {
        return in_array($field, $this->fields->toArray()) || $field instanceof IdFieldConfig;
    }

    /**
     * @param FieldConfig|string $field
     * @return mixed|null
     */
    public function getCellValue($field)
    {
        if (!$field instanceof FieldConfig) {
            $field = $this->grid->getConfig()->getColumn($field);
        }
        if (!$field instanceof IdFieldConfig && $this->uses($field)) {
            return parent::getCellValue($field);
        } else {
            return null;
        }
    }

    /**
     * Returns count of processed rows.
     *
     * @return int
     */
    public function getRowsProcessed()
    {
        return $this->rows_processed;
    }

    /**
     * Allows to specify list of fields
     * which will be used for totals calculation.
     *
     * @param array|string[] $fieldNames
     * @return $this
     */
    public function setFieldNames(array $fieldNames)
    {
        $this->field_names = $fieldNames;
        $this->src = [];
        foreach ($this->field_names as $name) {
            $this->src[$name] = 0;
        }
        return $this;
    }

    /**
     * Returns list of fields which are used for totals calculation.
     *
     * @return array|string[]
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
        return isset($this->field_operations[$fieldName])?$this->field_operations[$fieldName]:self::OPERATION_SUM;
    }
}
