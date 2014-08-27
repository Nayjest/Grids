<tr>
    <?php foreach($columns as $column): ?>
        <td class="column-<?= $column->getName() ?>">
            <?= $component->uses($column)?$column->getValue($component):'' ?>
        </td>
    <?php endforeach ?>
</tr>
