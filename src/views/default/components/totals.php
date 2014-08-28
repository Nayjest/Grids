<tr>
    <?php foreach($columns as $column): ?>
        <td
            class="column-<?= $column->getName() ?>"
            <?= $column->isHidden()?'style="display:none"':'' ?>
            >
            <?= $component->uses($column)?$column->getValue($component):'' ?>
        </td>
    <?php endforeach ?>
</tr>
