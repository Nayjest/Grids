<?php
/**
 * If we are inside workbench folder, include project autoloading, since there is possible dependencies from other
 * workbench libs that's are not placed to packages yet.
 */
$folder = basename(dirname(dirname(dirname(__DIR__))));
if (($folder === 'workbench' or $folder === 'vendor') and !defined('LARAVEL_START')) {
    include __DIR__ . '/../../../../bootstrap/autoload.php';
} else {
    include __DIR__ . '/../vendor/autoload.php';
}