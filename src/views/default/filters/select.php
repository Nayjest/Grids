<?php
/** @var Nayjest\Grids\Filter $filter */
?>
<select
    class="form-control input-sm"
    name="<?= $filter->getInputName() ?>"
    >
    <option value="">--//--</option>
    <?php foreach ($filter->getConfig()->getOptions() as $value => $label): ?>
        <?php $maybe_selected = ($filter->getValue() == $value and $filter->getValue() !== '' and $filter->getValue() !== null) ? 'selected="selected"':'' ?>
        <option <?= $maybe_selected ?> value="<?= $value ?>">
            <?= $label ?>
        </option>
    <?php endforeach ?>
</select>