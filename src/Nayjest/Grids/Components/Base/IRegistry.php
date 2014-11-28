<?php
namespace Nayjest\Grids\Components\Base;

use Nayjest\Grids\Grid;

/**
 * Interface IRegistry
 *
 * Interface of Grid components registry
 *
 * @package Nayjest\Grids\Components\Base
 */
interface IRegistry
{
    public function getComponents();

    public function getComponentByName($name);

    public function addComponent(IComponent $component);

    public function setComponents($components);

    public function makeComponent($class_name);

}