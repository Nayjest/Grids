<?php # ========== FILTERS ROW ========== ?>
<?php if($grid->getFiltering()->available()): ?>
    <tr>
            <?php foreach($columns as $column): ?>
                <td
                    class="column-<?= $column->getName() ?>"
                    <?= $column->isHidden()?'style="display:none"':'' ?>
                    >
                    <?php if ($column->hasFilters()): ?>
                        <?php foreach($column->getFilters() as $filter): ?>
                            <?= $grid->getFiltering()->render($filter) ?>
                        <?php endforeach ?>
                    <?php endif ?>
                </td>
            <?php endforeach ?>
            <?= $grid->getInputProcessor()->getSortingHiddenInputsHtml() ?>
    </tr>
<?php endif ?>