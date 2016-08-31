<small style="white-space: nowrap">
    <a
        title="<?= trans('grids::sort.ascending') ?>"
        <?php if($column->isSortedAsc()): ?>

            class="text-success"
        <?php else: ?>
            href="<?= $grid->getSorter()->link($column, 'ASC') ?>"
        <?php endif ?>
        >
        &#x25B2;
    </a>
    <a
        title="<?= trans('grids::sort.descending') ?>"
        <?php if($column->isSortedDesc()): ?>
            class="text-success"
        <?php else: ?>
            href="<?= $grid->getSorter()->link($column, 'DESC') ?>"
        <?php endif ?>
        >
        &#x25BC;
    </a>
</small>
