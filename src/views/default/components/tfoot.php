<?php
use \Nayjest\Grids\Components\TFoot;
/** @var TFoot $component */
echo $component->renderOpeningTag()
     . $component->renderComponents($component::SECTION_BEGIN)
?>
<tr>
    <td colspan="<?= $columns->count() ?>">
        <?= $component->getContent() . $component->renderComponents() ?>
    </td>
</tr>
<?= $component->renderComponents($component::SECTION_END)
    . $component->renderClosingTag();
?>