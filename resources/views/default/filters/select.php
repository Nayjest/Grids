<?php
/** @var Nayjest\Grids\Filter $filter */
/** @var Nayjest\Grids\SelectFilterConfig $cfg */
$cfg = $filter->getConfig();
$onchange = '';
if (method_exists($cfg, 'isSubmittedOnChange') && $cfg->isSubmittedOnChange()) {
    $onchange = 'onchange="this.form.submit()"';
}
?>
<select
    class="form-control input-sm"
    name="<?= $filter->getInputName() ?><?= $cfg->isMultipleMode() ? '[]' : '' ?>"
    <?= $onchange ?>
    <?= ($size = $cfg->getSize()) ? 'size="'.$size.'"' : '' ?>
    <?= ($cfg->isMultipleMode()) ? 'multiple="multiple"' : '' ?>
    >
    <?= (!$cfg->isMultipleMode()) ? '<option value="">--//--</option>' : '' ?>
    <?php foreach ($filter->getConfig()->getOptions() as $value => $label): ?>
        <?php
        $maybe_selected = (
            (
                (is_array($filter->getValue()) && in_array($value, $filter->getValue())) ||
                $filter->getValue() == $value
            )
            && $filter->getValue() !== ''
            && $filter->getValue() !== null
        ) ? 'selected="selected"' : ''
        ?>
        <option <?= $maybe_selected ?> value="<?= $value ?>">
            <?= $label ?>
        </option>
    <?php endforeach ?>
</select>
