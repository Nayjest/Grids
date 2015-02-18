<?php
/**
 * @author: Vitaliy Ofat <i@vitaliy-ofat.com>
 *
 * @var $grid Grid
 */
use Nayjest\Grids\Components\CsvExport;
use Nayjest\Grids\Grid;

?>
<a href="<?= $grid->getInputProcessor()->getUrl([CsvExport::INPUT_PARAM => 1]) ?>" class="btn btn-sm btn-info">Csv Export</a>