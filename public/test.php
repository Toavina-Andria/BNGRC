<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Testing Bootstrap Load</h2>";
echo "<pre>";

try {
    $ds = DIRECTORY_SEPARATOR;
    echo "Step 1: Loading bootstrap...\n";
    require(__DIR__ . $ds . '..' . $ds . 'app' . $ds . 'config' . $ds . 'bootstrap.php');
    echo "Step 2: Bootstrap loaded successfully!\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
} catch (Error $e) {
    echo "FATAL ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "</pre>";
