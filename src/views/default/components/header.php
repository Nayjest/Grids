<thead>
<?php
use \Nayjest\Grids\Components\Header;
/** @var Header $component */
echo $component->renderComponents(Header::SECTION_BEGIN);
?>
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
<?= $component->renderComponents(HEADER::SECTION_END) ?>
</thead>