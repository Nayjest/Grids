<?php
/** @var Nayjest\Grids\Components\Filter $component */
?>
<?php if($component->getLabel()): ?>
    <span><?= $component->getLabel() ?></span>
<?php endif ?>
<input
    class="form-control input-sm"
    style="display: inline; width: 80px; margin-right: 10px"
    type="text"
    name="<?= $component->getInputName() ?>"
    value="<?= $component->getValue() ?>"
    >
