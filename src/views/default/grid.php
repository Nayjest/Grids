<?php
/** @var Nayjest\Grids\DataProvider $data **/
/** @var Nayjest\Grids\Grid $grid **/
?>
<table class="table table-striped">
<thead>
    <tr>
        <?php /** @var Nayjest\Grids\FieldConfig $column **/ ?>
        <?php # ========== COLUMN HEADERS ========== ?>
        <?php foreach($columns as $column): ?>
        <th>
            <?php # ========== COLUMN HEADER ========== ?>
            <?= $column->getLabel() ?>

            <?php # ========== SORTING SWITCHERS ========== ?>
            <?php if ($column->isSortable()): ?>
                <small style="white-space: nowrap">
                <a
                    title="Sort ascending"
                    <?php if($column->isSortedAsc()): ?>

                        class="text-success"
                    <?php else: ?>
                        href="<?= $grid->getSorter()->link($column, 'ASC') ?>"
                    <?php endif ?>
                    >
                    &#x25B2;
                </a>
                <a
                    title="Sort descending"
                    <?php if($column->isSortedDesc()): ?>
                        class="text-success"
                    <?php else: ?>
                        href="<?= $grid->getSorter()->link($column, 'DESC') ?>"
                    <?php endif ?>
                    >
                    &#x25BC;
                </a>
                </small>
            <?php endif ?>
        </th>
        <?php endforeach ?>
        <?php # ========== ACTIONS COLUMN ========== ?>
        <?php if($grid->hasActionsColumn()): ?>
            <th>Actions</th>
        <?php endif ?>
    </tr>
    <?php # ========== FILTERS ROW ========== ?>
    <?php if($grid->getFiltering()->available()): ?>
        <tr>
            <form>
                <?php foreach($columns as $column): ?>
                    <td>
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
</thead>
<?php # ========== TABLE BODY ========== ?>
<tbody>
<?php while($row = $data->getRow()): ?>
    <tr>
        <?php /** @var Nayjest\Grids\FieldConfig $column **/ ?>
        <?php foreach($columns as $column): ?>
        <td>
            <?= $column->getValue($row) ?>
        </td>
        <?php endforeach ?>
    </tr>
<?php endwhile; ?>
</tbody>
<tfoot>
<?php if($row = $grid->getTotalsRow()): ?>
    <tr>
        <?php foreach($columns as $column): ?>
            <td>
                <?= $row->uses($column)?$column->getValue($row):'' ?>
            </td>
        <?php endforeach ?>
    </tr>
    <tr>
        <td colspan="<?= $columns->count() ?>">
            <?= $grid->links()  ?>
        </td>
    </tr>
<?php endif ?>
</tfoot>
</table>
