<?php
/** @var Nayjest\Grids\Components\SelectFilter $component */
$value = $component->getValue();
if (!is_array($value)) $value = [];
$id = uniqid() . mt_rand();
?>
<div class="btn-group">
    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
        <?= $component->getLabel() ?>
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu dropdown-menu-form"
        role="menu"
        id="<?=$id ?>"
        style="padding: 10px"
    >
        <li>
            <div>
                <label>
                    <input
                        type="checkbox"
                        class="checkAll"
                    >
                    <span><u>Check All</u></span>
                </label>
            </div>
        </li>
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
                        <span class="glyphicon glyphicon-collapse-down"></span>
                        <b><?= $label['name'] ?></b>
                    </a>

                    <div class="collapse<?=$class?>" id="collapse<?=$val?>" style="margin-left: 25px;">
                        <?php if (count($label['values']) > 1):?>
                            <div>
                                <label>
                                    <input
                                        type="checkbox"
                                        class="checkGroup"
                                    >
                                    <span><u>Check Group</u></span>
                                </label>
                            </div>
                        <?php endif ?>
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
        $('#<?= $id ?>.dropdown-menu').on('click', function(e) {
            if($(this).hasClass('dropdown-menu-form')) {
                e.stopPropagation();
            }
        });
        $('#<?= $id ?> input').change(function(){
            var $this = $(this);
            setTimeout(function(){
                var isCheckedGroup = true;
                $this.closest('li').find('input[type=checkbox]').not('.checkGroup').each(function(){
                    isCheckedGroup = isCheckedGroup && $(this).prop('checked');
                });
                $this.closest('li').find('.checkGroup').prop('checked', isCheckedGroup);
            }, 50);
            setTimeout(isCheckedAll,50);
        });
        $('#<?= $id ?> .collapsible').click(function(e){
            $(this).next('.collapse').toggleClass('in');
            $(this).find('i').toggleClass('glyphicon-collapse-down').toggleClass('glyphicon-collapse-up');
            e.preventDefault();
        });
        $('#<?= $id ?> .checkAll').change(function(e){
            var checked = $(this).prop('checked');
            $(this).closest('ul').find('input[type=checkbox]').prop('checked', checked);
        });
        $('#<?= $id ?> .checkGroup').change(function(e){
            var checked = $(this).prop('checked');
            $(this).closest('li').find('input[type=checkbox]').prop('checked', checked);
            setTimeout(isCheckedAll,50);
        });

        var isCheckedAll = function() {
            var isChecked = true;
            $('#<?= $id ?> input[type=checkbox]').not('.checkAll').each(function(){
                isChecked = isChecked && $(this).prop('checked');
            });
            $('#<?= $id ?> .checkAll').prop('checked', isChecked);
        };
    });

</script>
