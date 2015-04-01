<?php
/**
 * @author: Vitaliy Ofat <i@vitaliy-ofat.com>
 *
 * @var $grid Grid
 */
use Nayjest\Grids\Components\CsvExport;
use Nayjest\Grids\Grid;

?>
<span>
    <a
        href="<?= $grid->getInputProcessor()->getUrl([CsvExport::INPUT_PARAM => 1]) ?>"
        class="btn btn-sm btn-default"
        >
        <span class="glyphicon glyphicon-export"></span>
        CSV Export
    </a>
</span>