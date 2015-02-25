<?php
namespace Nayjest\Grids\Build\Setup;

use Nayjest\Builder\Blueprint;
use Nayjest\Builder\Instructions\Base\Instruction;
use Nayjest\Builder\Instructions\Mapping\BuildChildren;
use Nayjest\Builder\Instructions\Mapping\CustomInstruction;
use Nayjest\Builder\Instructions\Mapping\CustomMapping;
use Nayjest\Builder\Scaffold;
use Nayjest\Grids\Build\Instructions\BuildDataProvider;
use PhpParser\Node\Expr\Closure;

class Setup {

    protected function getConfigBlueprint()
    {

        $b = new Blueprint('Nayjest\Grids\GridConfig', [
            new BuildDataProvider(),
            new BuildChildren('components', $this->getComponentBlueprint()),
            new BuildChildren('columns', $this->getFieldBlueprint()),
        ]);
        return $b;
    }

    protected function getComponentBlueprint()
    {

        $b = new Blueprint('Nayjest\Grids\Components\ComponentInterface', [

            new CustomInstruction(function(Scaffold $s){
                if ($s->input instanceof Closure) {
                    $s->class = 'Nayjest\Grids\Components\RenderFunc';
                    $s->constructor_arguments = [$s->input];
                    $s->input = [];
                } elseif (is_string($s->input)) {
                    $s->class = 'Nayjest\Grids\Components\RenderFunc';
                    $out = $s->input;
                    $s->constructor_arguments = [function() use($out) {
                        return $out;
                    }];
                    $s->input = [];
                }
            }, Instruction::PHASE_PRE_INST),

            new CustomMapping('type', function($type, Scaffold $s) {
                if (strpos($type,'\\') !== false) {
                    $s->class = $type;
                } else {
                    $s->class = 'Nayjest\Grids\Components\\' . str_replace(
                        ' ',
                        '',
                        ucwords(str_replace(array('-', '_'), ' ', $type))
                    );
                }
            }, null, Instruction::PHASE_PRE_INST)
        ]);
        return $b;
    }

    protected function getFieldBlueprint()
    {
        $b = new Blueprint('Nayjest\Grids\FieldConfig', []);
        return $b;
    }


}