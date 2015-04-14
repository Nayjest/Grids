<?php
/** @var Nayjest\Grids\Components\Filters\DateRangePicker $component */
$id = uniqid();
?>
<?php if($component->getLabel()): ?>
    <span>
        <span class="glyphicon glyphicon-calendar"></span>
        <?= $component->getLabel() ?>
    </span>
<?php endif ?>
<input
    class="form-control input-sm"
    style="display: inline; width: 165px; margin-right: 10px"
    name="<?= $component->getInputName() ?>"
    type="text"
    id="<?= $id ?>"
    >

<script>
    $(function(){
        var options = <?= json_encode($component->getJsOptions())?>;
        if (!options.format) {
            options.format = 'YYYY-MM-DD';
        }
        var cb = function(start, end) {
            var text;
            if (start.isValid() && end.isValid()) {
                text = start.format(options.format) + ' — ' + end.format(options.format);
            } else {
                text = '';
            }
            $('#<?=$id?>').val(text);
        };
        var update_hidden = function(ev, picker) {
            $('[name="<?= $component->getStartInputName() ?>"]').val(picker.startDate.format(options.format));
            $('[name="<?= $component->getEndInputName() ?>"]').val(picker.endDate.format(options.format));
        };
        $('#<?= $id ?>')
            .daterangepicker(options, cb)
            .on('apply.daterangepicker', update_hidden)
            .on('change', function(){
                if (!$('#<?=$id?>').val()) {
                    $('[name="<?= $component->getStartInputName() ?>"]').val('');
                    $('[name="<?= $component->getEndInputName() ?>"]').val('');
                }
            });
        cb(
            moment("<?= $component->getStartValue() ?>"),
            moment("<?= $component->getEndValue() ?>")
        );
    })
</script>
<?= Form::hidden($component->getStartInputName(), $component->getStartValue()) ?>
<?= Form::hidden($component->getEndInputName(), $component->getEndValue()) ?>

