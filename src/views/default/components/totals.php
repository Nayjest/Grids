<tr>
    <?php foreach($columns as $column): ?>
        <td>
            <?= $component->uses($column)?$column->getValue($component):'' ?>
        </td>
    <?php endforeach ?>
</tr>
