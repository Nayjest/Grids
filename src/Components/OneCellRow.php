<?php
namespace Nayjest\Grids\Components;

use Nayjest\Grids\Components\Base\RenderableRegistry;

class OneCellRow extends RenderableRegistry
{
    protected $name = 'one_cell_row';

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $colspan = $this->grid->getConfig()->getColumns()->count();
        $opening = "<tr><td colspan=\"$colspan\">";
        $closing = '</td></tr>';
        return $this->wrapWithOutsideComponents(
            $opening . $this->renderInnerComponents() . $closing
        );
    }
}
