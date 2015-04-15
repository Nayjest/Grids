<?php
namespace Nayjest\Grids;

use Nayjest\Builder\Env;
use Nayjest\Grids\Build\Setup;

/**
 * Class Grids
 *
 * Facade for constructing grids using configurations.
 *
 * @package Nayjest\Grids
 */
class Grids {

    protected static $builder;

    /**
     * Returns builder instance.
     *
     * @return \Nayjest\Builder\Builder
     */
    protected static function getBuilder()
    {
        if (self::$builder === null) {
            $setup = new Setup();
            self::$builder = $setup->run();
        }
        return self::$builder;
    }

    /**
     * Creates grid using configuration.
     *
     * @param array $config
     * @return Grid
     */
    public static function make(array $config)
    {
        $builder = self::getBuilder();
        $configObject = $builder->build($config);
        $grid = new Grid($configObject);
        return $grid;
    }

    /**
     * Returns collection containing
     * blueprints required to construct grids from configuration.
     *
     * @return \Nayjest\Builder\BlueprintsCollection
     */
    public static function blueprints()
    {
        self::getBuilder();
        return Env::instance()->blueprints();
    }
}
