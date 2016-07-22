<form>
<?php
/** @var Nayjest\Grids\DataProvider $data **/
/** @var Nayjest\Grids\Grid $grid **/
?>
<table class="table table-striped" id="<?= $grid->getConfig()->getName() ?>">
<?= $grid->header() ? $grid->header()->render() : '' ?>
<?php # ========== TABLE BODY ========== ?>
<tbody>
<?php while($row = $data->getRow()): ?>
    <?= $grid->getConfig()->getRowComponent()->setDataRow($row)->render() ?>
<?php endwhile; ?>
</tbody>
<?= $grid->footer() ? $grid->footer()->render() : '' ?>
</table>
<?php # Hidden input for submitting form by pressing enter if there are no other submits ?>
<input type="submit" style="display: none;" />
</form>
