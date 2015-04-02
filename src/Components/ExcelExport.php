<?php

namespace Nayjest\Grids\Components;

use App;
use Event;
use Maatwebsite\Excel\Facades\Excel;
use Nayjest\Grids\Components\Base\RenderableComponent;
use Nayjest\Grids\DataProvider;
use Nayjest\Grids\DataRow;
use Nayjest\Grids\Grid;

/**
 * @author: Vitaliy Ofat <i@vitaliy-ofat.com>
 */
class ExcelExport extends RenderableComponent
{
    const NAME = 'excel_export';
    const INPUT_PARAM = 'xls';
    const DEFAULT_ROWS_LIMIT = 5000;

    protected $template = '*.components.excel_export';
    protected $name = ExcelExport::NAME;
    protected $render_section = THead::SECTION_END;
    protected $rows_limit = self::DEFAULT_ROWS_LIMIT;
    protected $extension = 'xls';

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
     * @var string
     */
    protected $sheetName;

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
                $this->renderExcel();
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
        return $this->fileName;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setSheetName($name)
    {
        $this->sheetName = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getSheetName()
    {
        return $this->sheetName;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setExtension($name)
    {
        $this->extension = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @return int
     */
    public function getRowsLimit()
    {
        return $this->rows_limit;
    }

    /**
     * @param int $rows_limit
     *
     * @return $this
     */
    public function setRowsLimit($rows_limit)
    {
        $this->rows_limit = $rows_limit;
        return $this;
    }


    protected function resetPagination(DataProvider $provider)
    {
        $provider->setPageSize($this->getRowsLimit());
        $provider->setCurrentPage(1);
    }


    protected function renderExcel()
    {
        // Build array
        $exportData = [];
        /** @var $provider DataProvider */
        $provider = $this->grid->getConfig()->getDataProvider();

        $exportData[] = $this->renderHeader();

        $this->resetPagination($provider);
        $provider->reset();
        /** @var DataRow $row */
        while ($row = $provider->getRow()) {
            $output = [];
            foreach ($this->grid->getConfig()->getColumns() as $column) {
                if (!$column->isExportHidden()) {

                    $output[] = $this->escapeString( $column->getValue($row) );
                }
            }
            $exportData[] = $output;
        }


        Excel::create($this->getFileName(), function($excel) use($exportData) {

            $excel->sheet($this->getSheetName(), function($sheet) use($exportData) {

                $sheet->fromArray($exportData,null, 'A1', false, false);

            });

        })->export($this->getExtension());



    }


    protected function escapeString($str)
    {
        return str_replace('"', '\'', strip_tags(html_entity_decode($str)));
    }

    protected function renderHeader()
    {
        $output = [];
        foreach ($this->grid->getConfig()->getColumns() as $column) {
            if (!$column->isExportHidden()) {
                $output[] = $this->escapeString($column->getLabel());
            }
        }
        return $output;
    }

}