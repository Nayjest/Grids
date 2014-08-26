<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19.08.2014
 * Time: 20:57
 */

namespace Nayjest\Grids;

class IdFieldConfig extends FieldConfig
{

    public function __construct()
    {
        $this->setName('ID');
    }

    public function getValue(DataRow $row)
    {
        return $row->getId();
    }

}