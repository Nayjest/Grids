<tfoot>
<?php
use \Nayjest\Grids\Components\Footer;
/** @var Nayjest\Grids\Components\Footer $component */
echo $component->renderComponents(Footer::SECTION_BEGIN);
?>
<tr>
    <td colspan="<?= $columns->count() ?>">
        <?= $component->renderComponents() ?>
    </td>
</tr>
<?php
echo $component->renderComponents(Footer::SECTION_END);
?>
</tfoot>