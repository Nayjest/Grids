<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 20.08.2014
 * Time: 19:46
 */

namespace Nayjest\Grids;

class SelectFilterConfig extends FilterConfig
{
    protected $template = '*.select';

    protected $options = [];

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }
} 