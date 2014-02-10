<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 10.02.14
 * Time: 15:39
 */

namespace Nayjest\Grids;


class Builder
{
    public static function make($cfg)
    {
        $columnsCfg = isset($cfg['columns']) ? $cfg['columns'] : [];

        $grid = new Grid();
        $grid->setColumns(
            static::buildColumns($columnsCfg)
        );
        $grid->setPagination(
            static::buildPagination($cfg)
        );
        $grid->addProvider(
          static::buildDataSource($cfg)
        );
        $grid->section = $cfg['section'];
        return $grid;

    }

    protected static function buildColumns($cfg)
    {
        $columns = [];
        foreach ($cfg as $key => $colData) {
            $col = new Column();
            $label = null;
            $name = null;
            if (is_string($colData)) {
                if (is_string($key)) {
                    $name = $key;
                    $label = $colData;
                } else {
                    $name = $label = $colData;
                }
            } else {
                if (isset($colData['name'])) {
                    $name = $colData['name'];
                } else {
                    $name = $key;
                }

                if (isset($colData['label'])) {
                    $label = $colData['label'];
                } else {
                    $label = $name;
                }
            }
            $col->setName($name);
            $col->setLabel($label);
            $columns[$name] = $col;
        }
        return $columns;
    }

    protected static function buildPagination($cfg)
    {
        $pageSize = isset($cfg['pageSize'])?$cfg['pageSize']:null;
        return new Pagination(
            $pageSize,
            isset($cfg['currentPage'])?$cfg['currentPage']:1,
            isset($cfg['paginationEnabled'])?$cfg['paginationEnabled']:(bool)$pageSize
        );
    }

    protected static function buildDataSource($cfg) {

        if (isset($cfg['data']['raw'])) {
            $provider = new ArrayDataProvider($cfg['data']['raw']);
            return $provider;
        }
        throw new \Exception('Grid data source is not specified');
    }

} 