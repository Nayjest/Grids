<?php
/**
 * @author: Vitaliy Ofat <i@vitaliy-ofat.com>
 *
 * @var $grid Grid
 */
use Nayjest\Grids\Components\ExcelExport;
use Nayjest\Grids\Grid;

?>
<span>
    <a
        href="<?= $grid->getInputProcessor()->getUrl([ExcelExport::INPUT_PARAM => 1]) ?>"
        class="btn btn-sm btn-default"
        >
        <span class="glyphicon glyphicon-export"></span>
        Excel Export
    </a>
</span>