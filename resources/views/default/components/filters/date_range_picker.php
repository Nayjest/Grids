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
                text = start.format(options.format) + '—' + end.format(options.format);
            } else {
                text = '';
            }
            $('#<?=$id?>').val(text);
        };
        var onApplyDate = function(ev, picker) {
            var start = $('[name="<?= $component->getStartInputName() ?>"]');
            start.val(picker.startDate.format(options.format));
            var end = $('[name="<?= $component->getEndInputName() ?>"]');
            end.val(picker.endDate.format(options.format));
            <?php if($component->isSubmittedOnChange()): ?>
            	end.get(0).form.submit();
            <?php endif ?>
        };
        $('#<?= $id ?>')
            .daterangepicker(options, cb)
            .on('apply.daterangepicker', onApplyDate)
            .on('change', function () {
              if (!$('#<?=$id?>').val()) {
                $('[name="<?= $component->getStartInputName() ?>"]').val('');
                $('[name="<?= $component->getEndInputName() ?>"]').val('');

                <?php if($component->isSubmittedOnChange()): ?>
                var end = $('[name="<?= $component->getEndInputName() ?>"]');
                end.get(0).form.submit();
                <?php endif ?>
              }
            })
            .on('cancel.daterangepicker', function () {
              $(this).val('');
              $(this).trigger("change");
            });
        cb(
            moment("<?= $component->getStartValue() ?>"),
            moment("<?= $component->getEndValue() ?>")
        );
    })
</script>
<?= Form::hidden($component->getStartInputName(), $component->getStartValue()) ?>
<?= Form::hidden($component->getEndInputName(), $component->getEndValue()) ?>

