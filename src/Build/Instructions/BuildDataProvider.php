<?php

namespace Nayjest\Grids\Build\Instructions;

use Nayjest\Builder\Instructions\Base\Instruction;
use Nayjest\Builder\Scaffold;

class BuildDataProvider extends Instruction
{
    protected $phase = self::PHASE_PRE_INST;

    /**
     * @param Scaffold $s
     */
    public function apply(Scaffold $s)
    {
        $src = $s->getInput('src');
        $s->excludeInput('src');
        $class = null;
        $arg = null;
        if (is_object($src)) {
            if (is_a($src, 'QueryBuilder')) {
                $class = '\Nayjest\Grids\EloquentDataProvider';
                $arg = $src;
            } elseif (is_a($src, '\Doctrine\DBAL\Query\QueryBuilder')) {
                $class = '\Nayjest\Grids\DbalDataProvider';
                $arg = $src;
            }
        } elseif (is_string($src)) {
            // model name
            if (
                class_exists($src, true) and
                is_subclass_of($src, '\Illuminate\Database\Eloquent\Model')
            ) {
                $class = '\Nayjest\Grids\EloquentDataProvider';
                $model = new $src;
                $arg = $model::newQuery();
            }
        }
        if ($class !== null and $arg !== null) {
            $provider = new $class($arg);
            $s->input['data_provider'] = $provider;
        }

    }
}