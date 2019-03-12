<?php

namespace Nayjest\Grids\Components;

use App\Components\Grid\ExtendedTableRow;
use App\Components\Grid\GridsToComponents;
use App\Http\Controllers\SupportersController;
use App\Topic;
use App\User;
use Event;
use Illuminate\Pagination\Paginator;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;
use Nayjest\Grids\Components\Base\RenderableComponent;
use Nayjest\Grids\Components\Base\RenderableRegistry;
use Nayjest\Grids\DataProvider;
use Nayjest\Grids\DataRow;
use Nayjest\Grids\FieldConfig;
use Nayjest\Grids\Grid;
use Illuminate\Support\Facades\Storage;


/**
 * Class ExcelExport
 *
 * The component provides control for exporting data to excel.
 *
 * @author: Alexander Hofmeister
 * @package Nayjest\Grids\Components
 */
class ExcelDispatchJobService extends RenderableComponent
{
    const NAME = 'excel_export';
    const INPUT_PARAM = 'xlsx';
    const DEFAULT_ROWS_LIMIT = 50000;

    protected $template = '*.components.excel_export';
    protected $name = ExcelDispatchJobService::NAME;
    protected $render_section = RenderableRegistry::SECTION_END;
    protected $rows_limit = self::DEFAULT_ROWS_LIMIT;
    protected $extension = 'xlsx';

    /**
     * @var string
     */
    protected $output;

    protected $data;

    protected $userId;

    protected $className;

    protected $config;



    /**
     * @var string
     */
    protected $fileName;

    /**
     * @var string
     */
    protected $sheetName;

    protected $ignored_columns = [];

    protected $is_hidden_columns_exported = false;

    protected $on_file_create;

    protected $on_sheet_create;

    /**
     * @return mixed
     */


    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param mixed $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param mixed $config
     */
    public function setClassName($className)
    {
        $this->className = $className;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $config
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    public function __construct($userId, $showId, $sheetName, $extension, $ignored_columns, $is_hidden_columns_exported, $rows_limit)
    {
        $this->setUserId($userId);
        $this->setSheetName($sheetName);
        $this->setExtension($extension);
        $this->setIgnoredColumns($ignored_columns);
        $this->setHiddenColumnsExported($is_hidden_columns_exported);
        $this->setRowsLimit($rows_limit);

        $user = User::find($this->userId);

        $controllers = GridsToComponents::getGridsControllers();
        $className = 'App\\Http\\Controllers\\' . $controllers[$sheetName];

        if($sheetName == 'topicQuestionsGrid') {
            $topic = Topic::find($showId);
            $this->config = $className::getQuestionsGridConfig($topic);
        }
        else{
            $this->config = $className::getGridConfig($user);
        }

        $grid = (new Grid($this->config->setRowComponent(new ExtendedTableRow(function ($model) {
            return $className::getRowAttributes($model);
        }))));

        $this->renderExcel();
    }

    /**
     * @param Grid $grid
     * @return null|void
     */
    public function initialize(Grid $grid)
    {
        $this->renderExcel();
    }

    /**
     * Sets name of exported file.
     *
     * @param string $name
     * @return $this
     */
    public function setFileName($name)
    {
        $this->fileName = $name;
        return $this;
    }

    /**
     * Returns name of exported file.
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName ?: $this->getConfig()->getName();
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
     * @param int $limit
     *
     * @return $this
     */
    public function setRowsLimit($limit = 50000)
    {
        $this->rows_limit = $limit;
        return $this;
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

    /**
     * @param FieldConfig $column
     * @return bool
     */
    protected function isColumnExported(FieldConfig $column)
    {
        return !in_array($column->getName(), $this->getIgnoredColumns())
            && ($this->isHiddenColumnsExported() || !$column->isHidden());
    }

    /**
     * @internal
     * @return array
     */
    public function getData()
    {
        // Build array
        $exportData = [];
        /** @var $provider DataProvider */
        $provider = $this->getConfig()->getDataProvider();

        $exportData[] = $this->getHeaderRow();

        $this->resetPagination($provider);
        $provider->reset();

        /** @var DataRow $row */
        while ($row = $provider->getRow()) {
            $output = [];
            foreach ($this->getConfig()->getColumns() as $column) {
                if ($this->isColumnExported($column)) {
                    $output[] = $this->escapeString($column->getValue($row));
                }
            }
            $exportData[] = $output;
        }
        return $exportData;
    }

    protected function renderExcel()
    {
        /** @var Excel $excel */
        $excel = app('excel');
        $excel
            ->create($this->getFileName(), $this->getOnFileCreate())
            ->store('xlsx',Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . 'public/excels/');
    }

    protected function escapeString($str)
    {
        return str_replace('"', '\'', strip_tags(html_entity_decode($str)));
    }

    protected function getHeaderRow()
    {
        $output = [];
        foreach ($this->getConfig()->getColumns() as $column) {
            if ($this->isColumnExported($column)) {
                $output[] = $this->escapeString($column->getLabel());
            }
        }
        return $output;
    }

    /**
     * @return string[]
     */
    public function getIgnoredColumns()
    {
        return $this->ignored_columns;
    }

    /**
     * @param string[] $ignoredColumns
     * @return $this
     */
    public function setIgnoredColumns(array $ignoredColumns)
    {
        $this->ignored_columns = $ignoredColumns;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isHiddenColumnsExported()
    {
        return $this->is_hidden_columns_exported;
    }

    /**
     * @param bool $isHiddenColumnsExported
     * @return $this
     */
    public function setHiddenColumnsExported($isHiddenColumnsExported)
    {
        $this->is_hidden_columns_exported = $isHiddenColumnsExported;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOnFileCreate()
    {
        if ($this->on_file_create === null) {
            $this->on_file_create = function (LaravelExcelWriter $excel) {
                $excel->sheet($this->getSheetName(), $this->getOnSheetCreate());

            };
        }
        return $this->on_file_create;
    }

    /**
     * @param callable $onFileCreate
     *
     * @return $this
     */
    public function setOnFileCreate($onFileCreate)
    {
        $this->on_file_create = $onFileCreate;
        return $this;
    }

    /**
     * @return callable
     */
    public function getOnSheetCreate()
    {
        if ($this->on_sheet_create === null) {
            $this->on_sheet_create = function (LaravelExcelWorksheet $sheet) {
//                for($i = 0; $i < count($data); $i++) {
//                    for ($j = 0; $j < count($data[$i]); $j++) {
//                        $sheet->appendRow(($i * 1000) + $j + 1, $data[$i][$j]);
//                    }
                //$sheet->fromArray($data, null, 'A1', false, false);
//                $data = $this->getData();
//                $data->chunk(100, function($rows) use ($sheet)
//                {

                $sheet->fromArray($this->getData(), null, 'A1', false, false);
//                });
            };
        }

        return $this->on_sheet_create;
    }

    /**
     * @param callable $onSheetCreate
     *
     * @return $this
     */
    public function setOnSheetCreate($onSheetCreate)
    {
        $this->on_sheet_create = $onSheetCreate;
        return $this;
    }
}