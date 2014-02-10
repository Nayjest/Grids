<?php
namespace Nayjest\Grids;


interface ColumnInterface
{
    public function getLabel();

    public function getName();

    public function renderHeader();

    public function render($row);
} 