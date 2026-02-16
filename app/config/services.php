<?php

use flight\Engine;
use flight\database\PdoWrapper;
use flight\debug\database\PdoQueryCapture;
use flight\debug\tracy\TracyExtensionLoader;
use Tracy\Debugger;


$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$basePath = dirname($scriptName);
if ($basePath === '/' || $basePath === '\\') {
    $basePath = '';
}

$app->set('base_path', $basePath);
Debugger::enable(); // Auto-detects environment
Debugger::$logDirectory = __DIR__ . $ds . '..' . $ds . 'log'; // Log directory
Debugger::$strictMode = true; // Show all errors (set to E_ALL & ~E_DEPRECATED for less noise)
if (Debugger::$showBar === true && php_sapi_name() !== 'cli') {
	(new TracyExtensionLoader($app)); // Load FlightPHP Tracy extensions
}
