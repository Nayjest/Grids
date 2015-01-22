<?php
/** @var Nayjest\Grids\Components\SelectFilter $component */
$value = $component->getValue();
is_array($value) or $value = [];
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
            <?php if(is_array($label)):?>
            <?php
                $class = '';
                if(array_intersect(array_keys($label['values']), array_keys($value))) {
                    $class = ' in';
                }
            ?>
            <li>
                <a href="#" data-target="#collapse<?=$val?>" class="collapsible">
                    <i class="glyphicon glyphicon-plus"></i>
                    <?= $label['name'] ?>
                </a>

                <div class="collapse<?=$class?>" id="collapse<?=$val?>">
                    <?php foreach($label['values'] as $option_val=>$option_label):?>
                        <div>
                        <label>
                            <input
                                type="checkbox"
                                <?php if(!empty($value[$option_val])) echo "checked='checked'" ?>
                                name="<?= $component->getInputName() ?>[<?= $option_val ?>]"
                                >
                            <span><?= $option_label ?></span>
                        </label>
                        </div>
                    <?php endforeach ?>
                </div>
            </li>
            <?php else:?>
            <li style="white-space: nowrap">
                <label>
                    <input
                        type="checkbox"
                        <?php if(!empty($value[$val])) echo "checked='checked'" ?>
                        name="<?= $component->getInputName() ?>[<?= $val ?>]"
                        >
                    <span><?= $label ?></span>
                </label>
            </li>
            <?php endif ?>
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
        });
        $('.collapsible').click(function(e){
            $(this).next('.collapse').toggleClass('in');
            $(this).find('i').toggleClass('glyphicon-plus').toggleClass('glyphicon-minus');
            e.preventDefault();
        });
    });

</script>