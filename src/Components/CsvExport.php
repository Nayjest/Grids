<?php

namespace Nayjest\Grids\Components;

use App;
use Event;
use Illuminate\Foundation\Application;
use Illuminate\Http\Response;
use Nayjest\Grids\Components\Base\RenderableComponent;
use Nayjest\Grids\Components\CsvExport\ForcedExitException;
use Nayjest\Grids\DataProvider;
use Nayjest\Grids\DataRow;
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
    const ROWS_PER_TIME = 5000;

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
        Event::listen(Grid::EVENT_PREPARE, function (Grid $grid) {
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
        $f = fopen('php://output', 'w');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="'. $this->getFileName() .'"');
        header("Pragma: no-cache");

        set_time_limit(0);

        /** @var $provider DataProvider */
        $provider = $this->grid->getConfig()->getDataProvider();

        $this->renderHeader($f);

        $pageNum = 1;

        $provider->setPageSize(static::ROWS_PER_TIME);
        $provider->setCurrentPage($pageNum);
        $provider->reset();
        /** @var DataRow $row */
        while ($row = $provider->getRow()) {
            $output = [];
            foreach ($this->grid->getConfig()->getColumns() as $column) {
                if (!$column->isHidden()) {
                    $output[] = $this->escapeString( $column->getValue($row) );
                }
            }
            fputcsv($f, $output, static::CSV_DELIMITER);

        }

        fclose($f);
        exit;
    }

    protected function escapeString($str)
    {
        return str_replace('"', '\'', strip_tags(html_entity_decode($str)));
    }

    protected function renderHeader($f)
    {
        $output = [];
        foreach ($this->grid->getConfig()->getColumns() as $column) {
            if (!$column->isHidden()) {
                $output[] = $this->escapeString($column->getLabel());
            }
        }
        fputcsv($f, $output, static::CSV_DELIMITER);
    }

}