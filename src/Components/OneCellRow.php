<?php
namespace Nayjest\Grids\Components;

use Nayjest\Grids\Components\Base\RenderableRegistry;

class OneCellRow extends RenderableRegistry
{
    protected $name = 'one_cell_row';

    public function render()
    {
        $colspan = $this->grid->getConfig()->getColumns()->count();
        return "\r\n\t<tr>\r\n\t\t<td colspan=$colspan>" . $this->renderComponents() . "\r\n\t\t</td>\r\n\t</tr>";
    }
}