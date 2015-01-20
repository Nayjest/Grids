<?php

namespace Nayjest\Grids\Components;

use Cache;
use Event;
use Illuminate\Http\Response;
use Nayjest\Grids\Components\Base\RenderableComponent;
use Nayjest\Grids\Components\THead;
use Nayjest\Grids\DataProvider;
use Nayjest\Grids\EloquentDataRow;
use Nayjest\Grids\Grid;

/**
 * @author: Vitaliy Ofat <i@vitaliy-ofat.com>
 */
class CsvExport extends RenderableComponent
{
    const NAME = 'csv_export';
    const INPUT_PARAM = 'csv';
    const CSV_DELIMITER = ';';
    const CSV_EXT = '.csv';

    protected $template = '*.components.csv_export';
    protected $name = CsvExport::NAME;
    protected $render_section = THead::SECTION_END;

    /**
     * @var Grid
     */
    protected $grid;

    /**
     * @var string
     */
    protected $output;

    /**
     * @var string
     */
    protected $fileName;

    /**
     * @param Grid $grid
     * @return null|void
     */
    public function initialize(Grid $grid)
    {
        parent::initialize($grid);
        Event::listen(Grid::EVENT_CREATE, function (Grid $grid) {
            $this->grid = $grid;
            if ($grid->getInputProcessor()->getValue(static::INPUT_PARAM, false)) {
                $this->renderCsv();
            }
        });
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setFileName($name)
    {
        $this->fileName = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName . static::CSV_EXT;
    }

    protected function setCsvHeaders(Response $response)
    {
        $response->header('Content-Type', 'application/csv');
        $response->header('Content-Disposition', 'attachment; filename=' . $this->getFileName());
        $response->header('Pragma', 'no-cache');
    }

    protected function renderCsv()
    {
        $key = $this->grid->getInputProcessor()->getUniqueRequestId();
        $caching_time = $this->grid->getConfig()->getCachingTime();

        if ($caching_time and ($output = Cache::get($key))) {
            $this->output = $output;
        } else {
            set_time_limit(0);
            $this->grid->getConfig()->getDataProvider()->setPageSize(PHP_INT_MAX);
            $this->grid->getConfig()->initialize($this->grid);

            $provider = $this->grid->getConfig()->getDataProvider();
            $provider->reset();

            $this->output = '';
            $this->renderHeader();
            $this->renderBody();

            if ($caching_time) {
                Cache::put($key, $this->output, $caching_time);
            }
        }

        $response = \Response::make($this->output);
        $this->setCsvHeaders($response);
        $response->send();

        \App::terminate(\Request::instance(), $response);
    }

    protected function escapeString($str)
    {
        return '"' . str_replace('"', '\'', strip_tags(html_entity_decode($str))) . '"';
    }

    protected function renderHeader()
    {
        foreach ($this->grid->getConfig()->getColumns() as $column) {
            if (!$column->isHidden()) {
                $this->output .= $this->escapeString($column->getLabel()) . static::CSV_DELIMITER;
            }
        }
        $this->output .= PHP_EOL;
    }

    protected function renderBody()
    {
        while ($row = $this->grid->getConfig()->getDataProvider()->getRow()) {
            foreach ($this->grid->getConfig()->getColumns() as $column) {
                if (!$column->isHidden()) {
                    $this->output .= $this->escapeString($column->getValue($row)) . static::CSV_DELIMITER;
                }
            }
            $this->output .= PHP_EOL;
        }
    }

}