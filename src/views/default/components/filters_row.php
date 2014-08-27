<?php # ========== FILTERS ROW ========== ?>
<?php if($grid->getFiltering()->available()): ?>
    <tr>
        <form>
            <?php foreach($columns as $column): ?>
                <td class="column-<?= $column->getName() ?>">
                    <?php if ($column->hasFilters()): ?>
                        <?php foreach($column->getFilters() as $filter): ?>
                            <?= $grid->getFiltering()->render($filter) ?>
                        <?php endforeach ?>
                    <?php endif ?>
                </td>
            <?php endforeach ?>
            <td>
                <?= $grid->getInputProcessor()->getSortingHiddenInputsHtml() ?>
                <button type="submit" class="btn btn-primary">Filter</button>
            </td>
        </form>
    </tr>
<?php endif ?>