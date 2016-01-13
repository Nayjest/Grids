<?php
namespace Nayjest\Grids\Components\Filters;

use Carbon\Carbon;
use Nayjest\Grids\Components\Filter;
use Nayjest\Grids\DataProvider;

/**
 * Class DateRangePicker
 *
 * Date Range Picker for Bootstrap.
 * https://github.com/dangrossman/bootstrap-daterangepicker
 *
 * This component does not includes javascript & styles required to work with bootstrap-daterangepicker.
 * You need to include it manually to your pages/layout.
 *
 * @package Nayjest\Grids\Components\Filters
 */
class DateRangePicker extends Filter
{
    protected $js_options;

    protected $use_clear_button;

    protected $template = '*.components.filters.date_range_picker';
    
    protected $is_submitted_on_change = false;

    /**
     * Returns javascript options
     *
     * Available options:
     * @see https://github.com/dangrossman/bootstrap-daterangepicker#options
     *
     * @return array
     */
    public function getJsOptions()
    {
        if (!$this->js_options) {
            $this->js_options = $this->getDefaultJsOptions();
        }
        return $this->js_options;
    }

    /**
     * Sets javascript options
     *
     * Available options:
     * @see https://github.com/dangrossman/bootstrap-daterangepicker#options
     *
     * @param array $options
     */
    public function setJsOptions($options)
    {
        $this->js_options = $options;
        return $this;
    }
    
    /**
     * Returns true if form must be submitted immediately
     * when filter value selected.
     *
     * @return bool
     */
    public function isSubmittedOnChange()
    {
        return $this->is_submitted_on_change;
    }
    
    /**
     * Allows to submit form immediately when filter value selected.
     *
     * @param bool $isSubmittedOnChange
     * @return $this
     */
    public function setSubmittedOnChange($isSubmittedOnChange)
    {
        $this->is_submitted_on_change = $isSubmittedOnChange;
        return $this;
    }

    public function getStartValue()
    {
        $from_input = $this
            ->grid
            ->getInputProcessor()
            ->getFilterValue($this->name . '_start');
        if ($from_input === null) {
            return $this->getDefaultStartValue();
        } else {
            return $from_input;
        }
    }


    public function getEndValue()
    {
        $from_input = $this
            ->grid
            ->getInputProcessor()
            ->getFilterValue($this->name . '_end');
        if ($from_input === null) {
            return $this->getDefaultEndValue();
        } else {
            return $from_input;
        }
    }

    public function getValue()
    {
        return [$this->getStartValue(), $this->getEndValue()];
    }

    /**
     * Returns true if non-empty value specified for the filter.
     *
     * @return bool
     */
    protected function hasValue()
    {
        list($start, $end) = $this->getValue();
        return $start !== null && $start !== '' && $end !== null && $end !== '';
    }

    /**
     * Returns default javascript options
     *
     * Available options:
     * @see https://github.com/dangrossman/bootstrap-daterangepicker#options
     *
     * @return array
     */
    protected function getDefaultJsOptions()
    {
        $carbon = new Carbon();
        $prev_month = Carbon::now()->startOfMonth()->subWeek();
        $today = Carbon::now();
        $res = [
            'format' => 'YYYY-MM-DD',
            'ranges' => [
                'previous_month' => [
                    'Previous month (' . $prev_month->format('F') . ')',
                    [
                        $prev_month->startOfMonth()->format('Y-m-d'),
                        $prev_month->endOfMonth()->format('Y-m-d'),
                    ]
                ],
                'current_month' => [
                    'Cur. month (' . date('F'). ')',
                    [
                        $carbon->startOfMonth()->format('Y-m-d'),
                        $carbon->endOfMonth()->format('Y-m-d')
                    ]
                ],
                'last_week' => [
                    'This Week',
                    [
                        $carbon->startOfWeek()->format('Y-m-d'),
                        $carbon->endOfWeek()->format('Y-m-d')
                    ]
                ],
                'last_14' => [
                    'Last 14 days',
                    [
                        Carbon::now()->subDays(13)->format('Y-m-d'),
                        $today->format('Y-m-d')
                    ]
                ],

            ],
        ];
        // will not set dates when '' passed but set default date when null passed
        if ($this->getStartValue()) {
            $res['startDate'] = $this->getStartValue();
        }
        if ($this->getEndValue()) {
            $res['endDate'] = $this->getEndValue();
        }
        return $res;
    }

    public function getDefaultStartValue()
    {
        return $this->getDefaultValue()[0];
    }

    public function getDefaultEndValue()
    {
        return $this->getDefaultValue()[1];
    }

    /**
     * Returns default filter value as [$startDate, $endDate]
     *
     * @return array
     */
    public function getDefaultValue()
    {
        return is_array($this->default_value) ? $this->default_value : [
            Carbon::now()->subWeek()->format('Y-m-d'),
            Carbon::now()->format('Y-m-d'),
        ];
    }

    public function getStartInputName()
    {
        $key = $this->grid->getInputProcessor()->getKey();
        return "{$key}[filters][{$this->name}_start]";
    }

    public function getEndInputName()
    {
        $key = $this->grid->getInputProcessor()->getKey();
        return "{$key}[filters][{$this->name}_end]";
    }

    public function getFilteringFunc()
    {
        if (!$this->filtering_func) {
            $this->filtering_func = $this->getDefaultFilteringFunc();
        }
        return $this->filtering_func;
    }

    protected function getDefaultFilteringFunc()
    {
        return function($value, DataProvider $provider) {
            $provider->filter($this->getName(), '>=', $value[0]);
            $provider->filter($this->getName(), '<=', $value[1]);
        };
    }
}
