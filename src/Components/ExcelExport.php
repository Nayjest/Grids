<?php

namespace Nayjest\Grids\Components;

use App\Jobs\ExportExcel;
use Event;
use Nayjest\Grids\Components\Base\RenderableComponent;
use Nayjest\Grids\Grid;

class ExcelExport extends RenderableComponent
{
    private $baseName;
    private $date;
    private $config;

    const NAME = 'excel_export';
    protected $template = '*.components.excel_export';

    const INPUT_PARAM = 'xlsx';

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

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
        $this->config = $grid->getConfig();
            if ($grid->getInputProcessor()->getValue(static::INPUT_PARAM, false)) {
                dispatch(new ExportExcel($this->getDate(), $this->getBaseName(), $this->config));
            }
        });
    }
}