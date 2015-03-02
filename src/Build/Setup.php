<?php
namespace Nayjest\Grids\Build;

use Closure;
use DB;
use Nayjest\Builder\Blueprint;
use Nayjest\Builder\BlueprintsCollection;
use Nayjest\Builder\Builder;
use Nayjest\Builder\Env;
use Nayjest\Builder\Instructions\Base\Instruction;
use Nayjest\Builder\Instructions\Mapping\BuildChildren;
use Nayjest\Builder\Instructions\CustomInstruction;
use Nayjest\Builder\Instructions\Mapping\CustomMapping;
use Nayjest\Builder\Instructions\Mapping\Rename;
use Nayjest\Builder\Instructions\SimpleValueAsField;
use Nayjest\Builder\Scaffold;
use Nayjest\Grids\Build\Instructions\BuildDataProvider;
use Nayjest\Grids\EloquentDataProvider;

/**
 * Class Setup
 *
 * This class prepares environment for nayjest/builder package for usage with grids.
 * Integration with nayjest/builder package allows to construct grids from configuration in form of php array
 *
 * @See \Grids::make
 *
 * @internal
 * @package Nayjest\Grids\Build
 */
class Setup
{

    const COLUMN_CLASS = 'Nayjest\Grids\FieldConfig';
    const COMPONENT_CLASS = 'Nayjest\Grids\Components\Base\ComponentInterface';
    const GRID_CLASS = 'Nayjest\Grids\GridConfig';
    const FILTER_CLASS = 'Nayjest\Grids\FilterConfig';

    /**
     * @var BlueprintsCollection
     */
    protected $blueprints;

    public function run()
    {
        $this->blueprints = Env::instance()->blueprints();
        $this->blueprints
            ->add($this->makeFilterBlueprint())
            ->add($this->makeFieldBlueprint())
            ->add($this->makeComponentBlueprint())
            ->add($config_blueprint = $this->makeConfigBlueprint());
        return new Builder($config_blueprint);
    }

    protected function makeConfigBlueprint()
    {

        $b = new Blueprint(self::GRID_CLASS, [
            new BuildDataProvider(),
            new CustomInstruction(function (Scaffold $s) {
                /** @var EloquentDataProvider $provider */
                $provider = $s->getInput('data_provider');
                $is_eloquent = $provider  instanceof EloquentDataProvider;

                if ($is_eloquent and !$s->getInput('columns')) {
                    $table = $provider->getBuilder()->getModel()->getTable();
                    $columns = DB
                        ::connection()
                        ->getSchemaBuilder()
                        ->getColumnListing($table);
                    $s->input['columns'] = $columns;

                }
            }, Instruction::PHASE_PRE_INST),
            new BuildChildren(
                'components',
                $this->blueprints->getFor(self::COMPONENT_CLASS)
            ),
            new BuildChildren(
                'columns',
                $this->blueprints->getFor(self::COLUMN_CLASS)
            ),
        ]);
        return $b;
    }

    protected function makeComponentBlueprint()
    {
        $blueprint = new Blueprint(self::COMPONENT_CLASS, [

            new CustomInstruction(function (Scaffold $s) {
                if ($s->input instanceof Closure) {
                    $s->class = 'Nayjest\Grids\Components\RenderFunc';
                    $s->constructor_arguments = [$s->input];
                    $s->input = [];
                } elseif (is_string($s->input)) {
                    $s->class = 'Nayjest\Grids\Components\RenderFunc';
                    $out = $s->input;
                    $s->constructor_arguments = [function () use ($out) {
                        return $out;
                    }];
                    $s->input = [];
                }
            }, Instruction::PHASE_PRE_INST),
            new CustomMapping('type', function ($type, Scaffold $s) {
                if (strpos($type, '\\') !== false) {
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
        $blueprint->add(new BuildChildren('components', $blueprint));
        return $blueprint;
    }

    protected function makeFilterBlueprint()
    {
        return new Blueprint(self::FILTER_CLASS, [
            new SimpleValueAsField('name'),
            new Rename(0,'name'),
            new Rename(1,'operator'),
        ]);
    }

    protected function makeFieldBlueprint()
    {
        return new Blueprint(self::COLUMN_CLASS, [
            new SimpleValueAsField('name'),
            new Rename(0,'name'),
            new BuildChildren(
                'filters',
                $this->blueprints->getFor(self::FILTER_CLASS)
            ),

        ]);
    }


}