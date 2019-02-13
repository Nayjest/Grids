<?php

namespace Nayjest\Grids\Components;

use Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\Paginator;
use Illuminate\Foundation\Application;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;
use Nayjest\Grids\Components\Base\RenderableComponent;
use Nayjest\Grids\Components\Base\RenderableRegistry;
use Nayjest\Grids\DataProvider;
use Nayjest\Grids\DataRow;
use Nayjest\Grids\FieldConfig;
use Nayjest\Grids\Grid;

class ExcelDownload extends RenderableComponent
{
    private $baseName;
    private $date;

    const NAME = 'excel_download';
    const INPUT_PARAM = 'dld';

    protected $template = '*.components.excel_download';
    protected $name = ExcelDownload::NAME;
    protected $render_section = RenderableRegistry::SECTION_END;
    protected $extension = 'dld';

    /**
     * @return mixed
     */
    public function getBaseName()
    {
        return $this->baseName;
    }

    /**
     * @param mixed $baseName
     */
    public function setBaseName($baseName)
    {
        $this->baseName = $baseName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }
    

    public function initialize(Grid $grid)
    {
        parent::initialize($grid);
        Event::listen(Grid::EVENT_PREPARE, function (Grid $grid) {
            if ($this->grid !== $grid) {
                return;
            }
            if ($grid->getInputProcessor()->getValue(static::INPUT_PARAM, false)) {
                foreach (glob(Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . 'public/excels/*.xlsx', GLOB_BRACE) as $fileName) {
                    if (strpos($fileName, $this->getBaseName())) {
                        $this->responseWithProtectedFile($fileName);
                    }
                }
            }
        });
    }
    public function responseWithProtectedFile($path)
    {
        $fileContents = Storage::disk('public')->get('/excels/supporters_2019-02-06-165444.xlsx');
        $fileMimeType = Storage::getMimeType($path);
        return response($fileContents, 200)->header('Content-Type', $fileMimeType);
    }
}