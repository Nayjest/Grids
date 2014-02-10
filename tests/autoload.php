<?php
/**
 * If we are inside workbench folder, include project autoloading, since there is possible dependencies from other
 * workbench libs that's are not placed to packages yet.
 */

if (basename(dirname(dirname(dirname(__DIR__)))) === 'workbench' and !defined('LARAVEL_START')) {
    include __DIR__ . '/../../../../bootstrap/autoload.php';
} else {
    include __DIR__ . '/../vendor/autoload.php';
}