<?php
/**
 * @author: Vitaliy Ofat <i@vitaliy-ofat.com>
 *
 * @var $grid Nayjest\Grids\Grid
 */
use Nayjest\Grids\Components\ExcelDownload;
?>
<span style="visibility:hidden">
    <a
        href="<?= $grid
            ->getInputProcessor()
            ->getUrl(['dld' => 1]);
        ?>"
        class="btn btn-sm btn-default"
    >
        <span class="glyphicon glyphicon-download"></span>
        Excel Download
    </a>
</span>