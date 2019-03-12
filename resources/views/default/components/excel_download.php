<?php
/**
 * @author: Vitaliy Ofat <i@vitaliy-ofat.com>
 *
 * @var $grid Nayjest\Grids\Grid
 */
use Nayjest\Grids\Components\ExcelDownload;
$gridName = $grid->getConfig()->getName();
$visibility = DB::table('jobs')->select('id')->where('payload', 'LIKE', '%ExportExcel%')->where('payload','LIKE',"%$gridName%")->get();
?>
<span style="visibility:<?=file_exists(storage_path() . '/app/public/excels/' . $gridName . '.xlsx') && !!!count($visibility) ? "visible" : "hidden" ?>" >
    <a
        href="/download/<?= $gridName ?>" target="_blank"
        class="btn btn-sm btn-default"
    >
        <span class="glyphicon glyphicon-download"></span>
        Excel Download
    </a>
</span>