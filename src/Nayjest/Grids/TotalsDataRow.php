<?php
namespace Nayjest\Grids;

use Event;
use Illuminate\Support\Collection;

class TotalsDataRow extends ArrayDataRow
{

    /** @var \Illuminate\Support\Collection|FieldConfig[] */
    protected $fields;

    protected function listen(DataProvider $dp)
    {
        Event::listen(
            DataProvider::EVENT_FETCH_ROW,
            function(DataRow $row, DataProvider $provider) use($dp) {
                if($dp !== $provider) return;
                foreach($this->fields as $field) {
                    $this->src[$field->getName()] += $row->getCellValue($field);
                }
            }
        );
    }
    /**
     * @param FieldConfig[]|Collection $fields
     * @param DataProvider $dp
     */
    public function __construct($fields, DataProvider $dp)
    {
        $this->fields = Collection::make($fields);
        $this->id = 'Totals';
        $this->src = [];
        foreach ($fields as $field) {
            $this->src[$field->getName()] = 0;
        }
        $this->listen($dp);
    }

    /**
     * @param FieldConfig $field
     * @return bool
     */
    public function uses(FieldConfig $field)
    {
        return in_array($field, $this->fields->toArray()) or $field instanceof IdFieldConfig;
    }

    public function getCellValue(FieldConfig $field)
    {
        if ($this->uses($field) and !$field instanceof IdFieldConfig) {
            return parent::getCellValue($field);
        } else {
            return null;
        }
    }
} 