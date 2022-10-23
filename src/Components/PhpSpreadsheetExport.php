<?php

namespace Nayjest\Grids\Components;


use Generator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Maatwebsite\Excel\Concerns\FromGenerator;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Exporter;
use Nayjest\Grids\Grid;


/**
 * When using Maatwebsite/Excel v3 (or newer) which uses PhpSpreadsheet in place of PhpExcel
 * use this export component in place of ExcelExport component. It provides same funcionality
 * but uses newer API
 * 
 * Be careful this file is compatible with php version 7 and above. Don't try to use this
 * component in projects using older PHP versions.
 * 
 * @author: Janusz Paszynski
 * @package Nayjest\Grids\Components
 */
class PhpSpreadsheetExport extends ExcelExport implements FromGenerator, WithHeadings, WithEvents
{
    /** @var Exporter */
    protected $exporter;

    public function __construct()
    {
        if(!interface_exists('Maatwebsite\Excel\Exporter')) {
            throw new \RuntimeException('It seems there is no Maatwebsite v3 installed. Install it or use ExcelExport component');
        }
    }

    public function initialize(Grid $grid)
    {
        parent::initialize($grid);
        $this->exporter = \app(Exporter::class);
    }

    /** @inheritDoc */
    protected function renderExcel()
    {
        $response = $this->exporter->download($this, sprintf('%s.%s', $this->getFileName(), $this->getExtension()), Excel::XLSX);
        throw new HttpResponseException($response);
    }

    /** @inheritDoc */
    public function generator(): Generator
    {
        $columnsConfig = $this->grid->getConfig()->getColumns();
        $provider = $this->grid->getConfig()->getDataProvider();
        $this->resetPagination($provider);
        $provider->reset();
        while($row = $provider->getRow()) {
            $output = [];
            foreach($columnsConfig as $column) {
                if ($this->isColumnExported($column)) {
                    $output[] = $this->escapeString($column->getValue($row));
                }
            }
            yield $output;
        }
    }

	/**
	 *
	 * @return array
	 */
	function headings(): array
    {
        return $this->getHeaderRow();
	}

	/**
	 *
	 * @return array
	 */
	function registerEvents(): array
    {
        return [
            BeforeSheet::class => function(BeforeSheet $event) {
                $sheetTitle = $this->getSheetName();
                if ($sheetTitle) {
                    $event->sheet->setTitle($sheetTitle);
                }
            }
        ];
	}

}
