<form>
<?php
/** @var Nayjest\Grids\DataProvider $data **/
/** @var Nayjest\Grids\Grid $grid **/
?>
<table class="table table-striped" id="<?= $grid->getConfig()->getName() ?>">
<?= $grid->header() ?>
<?php # ========== TABLE BODY ========== ?>
<tbody>
<?php while($row = $data->getRow()): ?>
    <tr>
        <?php /** @var Nayjest\Grids\FieldConfig $column **/ ?>
        <?php foreach($columns as $column): ?>
        <td
            class="column-<?= $column->getName() ?>"
            <?= $column->isHidden()?'style="display:none"':'' ?>
            >
            <?= $column->getValue($row) ?>
        </td>
        <?php endforeach ?>
    </tr>
<?php endwhile; ?>
</tbody>
<?= $grid->footer() ?>
</table>
</form>