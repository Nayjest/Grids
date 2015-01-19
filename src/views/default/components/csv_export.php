<?php
/**
 * @author: Vitaliy Ofat <i@vitaliy-ofat.com>
 *
 * @var $grid Grid
 */
use Nayjest\Grids\Grid;

?>
<a href="<?= $grid->getInputProcessor()->setValue(\Nayjest\Grids\Components\CsvExport::INPUT_PARAM, 1)->getUrl() ?>" class="btn btn-sm btn-info">Csv Export</a>