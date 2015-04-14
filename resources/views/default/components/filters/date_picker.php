<?php
/** @var Nayjest\Grids\Components\Filter $component */
$id = uniqid();
?>
<?php if($component->getLabel()): ?>
    <span><?= $component->getLabel() ?></span>
<?php endif ?>
<input
    class="form-control input-sm"
    style="display: inline; width: 85px; margin-right: 10px"
    name="<?= $component->getInputName() ?>"
    type="text"
    value="<?= $component->getValue() ?>"
    id="<?= $id ?>"
    >
<script>
    $(function(){
        $('#<?= $id ?>').datepicker({format: 'yyyy-mm-dd'});
    })
</script>
