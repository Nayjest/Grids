<?php
/** @var Nayjest\Grids\Components\RecordsPerPage $component */
?>
<span><?php echo $component->getName(); ?></span>

<?php
echo \Form::select(
    $component->getInputName(),
    $component->getVariants(),
    $component->getValue(),
    [
        'class' => "form-control input-sm grids-control-records-per-page",
        'style' => 'display: inline; width: 80px; margin-right: 10px'
    ]
);
?>
