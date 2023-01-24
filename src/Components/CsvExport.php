<?php

namespace Nayjest\Grids\Components;

use Event;
use Illuminate\Pagination\Paginator;
use Illuminate\Foundation\Application;
use Illuminate\Http\Response;
use Nayjest\Grids\Components\Base\RenderableComponent;
use Nayjest\Grids\Components\Base\RenderableRegistry;
use Nayjest\Grids\DataProvider;
use Nayjest\Grids\DataRow;
use Nayjest\Grids\FieldConfig;
use Nayjest\Grids\Grid;

/**
 * Class CsvExport
 *
 * The component provides control for exporting data to CSV.
 *
 * @author: Vitaliy Ofat <i@vitaliy-ofat.com>
 * @package Nayjest\Grids\Components
 */
class CsvExport extends RenderableComponent
{
    const NAME = 'csv_export';
    const INPUT_PARAM = 'csv';
    const CSV_DELIMITER = ';';
    const CSV_EXT = '.csv';
    const DEFAULT_ROWS_LIMIT = 5000;

    protected $template = '*.components.csv_export';
    protected $name = CsvExport::NAME;
    protected $render_section = RenderableRegistry::SECTION_END;
    protected $rows_limit = self::DEFAULT_ROWS_LIMIT;
    /**
     * List of columns names that are not to be exported to CSV file. It is not verified if column name
     * exists in result data row
     * 
     * @var string[]
     */
    protected $ignored_columns = [];
    /**
     * Flag determining if hidden columns should be rendered into CSV file. By default hidden files
     * are ignored.
     * 
     * @var bool
     */
    protected $is_hidden_columns_exported = false;

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
            if ($this->grid !== $grid) {
                return;
            }
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

    /**
     * @return int
     */
    public function getRowsLimit()
    {
        return $this->rows_limit;
    }

    /**
     * @param int $limit
     *
     * @return $this
     */
    public function setRowsLimit($limit)
    {
        $this->rows_limit = $limit;
        return $this;
    }

    /**
     * Returns list of previously set ignored columns
     * 
     * @return array|null
     */
    public function getIgnoredColumns()
    {
        return $this->ignored_columns;
    }

    /**
     * Returns flag determining if hidden columns are exported
     * 
     * @return bool
     */
    public function isHiddenColumnsExported()
    {
        return $this->is_hidden_columns_exported;
    }
    
    /**
     * Sets flag determining if hidden columns should be exported to CSV file. By default
     * hidden columns are all ignored
     *
     * @param bool $isHiddenColumnsExported
     * @return $this
     */
    public function setIsHiddenColumnsExported($isHiddenColumnsExported)
    {
        $this->is_hidden_columns_exported = $isHiddenColumnsExported;
        return $this;
    }

    /**
     * Sets list of columns, specified by name, to be ignored on export
     * 
     * @param array|null $ignored_columns
     * @return $this
     */
    public function setIgnoredColumns($ignored_columns)
    {
        $this->ignored_columns = $ignored_columns;
        return $this;
    }

    /**
     * Helper method determining if provided column should be exported into CSV
     * 
     * @param FieldConfig $column
     * @return bool
     */
    protected function isColumnExported($column)
    {
        return !in_array($column->getName(), $this->ignored_columns)
            && ($this->is_hidden_columns_exported || !$column->isHidden());
    }

    protected function setCsvHeaders(Response $response)
    {
        $response->header('Content-Type', 'application/csv');
        $response->header('Content-Disposition', 'attachment; filename=' . $this->getFileName());
        $response->header('Pragma', 'no-cache');
    }

    protected function resetPagination(DataProvider $provider)
    {
        if (version_compare(Application::VERSION, '5.0.0', '<')) {
            $provider->getPaginationFactory()->setPageName('page_unused');
        } else {
            Paginator::currentPageResolver(function () {
                return 1;
            });
        }
        $provider->setPageSize($this->getRowsLimit());
        $provider->setCurrentPage(1);
    }

    protected function renderCsv()
    {
        $file = fopen('php://output', 'w');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="'. $this->getFileName() .'"');
        header('Pragma: no-cache');

        set_time_limit(0);

        /** @var $provider DataProvider */
        $provider = $this->grid->getConfig()->getDataProvider();

        $this->renderHeader($file);

        $this->resetPagination($provider);
        $provider->reset();
        /** @var DataRow $row */
        while ($row = $provider->getRow()) {
            $output = [];
            foreach ($this->grid->getConfig()->getColumns() as $column) {
                if ($this->isColumnExported($column)) {
                    $output[] = $this->escapeString( $column->getValue($row) );
                }
            }
            fputcsv($file, $output, static::CSV_DELIMITER);

        }

        fclose($file);
        exit;
    }

    /**
     * @param string $str
     * @return string
     */
    protected function escapeString($str)
    {
        $str = html_entity_decode($str);
        $str = strip_tags($str);
        $str = str_replace('"', '\'', $str);
        $str = preg_replace('/\s+/', ' ', $str); # remove double spaces
        $str = trim($str);
        return $str;
    }

    /**
     * @param resource $file
     */
    protected function renderHeader($file)
    {
        $output = [];
        foreach ($this->grid->getConfig()->getColumns() as $column) {
            if ($this->isColumnExported($column)) {
                $output[] = $this->escapeString($column->getLabel());
            }
        }
        fputcsv($file, $output, static::CSV_DELIMITER);
    }
}
