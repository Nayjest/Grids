<?php
/** @var Nayjest\Grids\Components\SelectFilter $component */
$value = $component->getValue();
$id = uniqid();
?>
<div class="btn-group">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
        <?= $component->getLabel() ?>
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu dropdown-menu-form"
        role="menu"
        id="<?=$id ?>"
        style="padding: 10px"
        >
        <?php foreach($component->getVariants() as $val => $label): ?>
            <li style="white-space: nowrap">
                <input
                    type="checkbox"
                    <?php if(!empty($value[$val])) echo "checked='checked'" ?>
                    name="<?= $component->getInputName() ?>[<?= $val ?>]"
                    />
                <span><?= $label ?></span>
            </li>
        <?php endforeach ?>
    </ul>
</div>
<script>
    $(function(){
        $('.dropdown-menu').on('click', function(e) {
            if($(this).hasClass('dropdown-menu-form')) {
                e.stopPropagation();
            }
        });
        $('<?= $id ?> input').change(function($input) {
            console.log($input)
        })
    });

</script>