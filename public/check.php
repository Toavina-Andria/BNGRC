<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Step-by-Step Testing</h2>";
echo "<pre>";

try {
    echo "1. Loading vendor autoload...\n";
    $ds = DIRECTORY_SEPARATOR;
    require(__DIR__ . $ds . '..' . $ds . 'vendor' . $ds . 'autoload.php');
    echo "   ✓ Vendor loaded\n\n";
    
    echo "2. Checking config file...\n";
    $config_path = __DIR__ . $ds . '..' . $ds . 'app' . $ds . 'config' . $ds . 'config.php';
    if (file_exists($config_path)) {
        echo "   ✓ Config file exists\n\n";
    } else {
        echo "   ✗ Config file NOT found at: $config_path\n\n";
    }
    
    echo "3. Loading config...\n";
    $config = require($config_path);
    echo "   ✓ Config loaded\n";
    echo "   Database: " . ($config['database']['dbname'] ?? 'not set') . "\n";
    echo "   Host: " . ($config['database']['host'] ?? 'not set') . "\n\n";
    
    echo "4. Testing database connection...\n";
    $dsn = 'mysql:host=' . $config['database']['host'] . ';dbname=' . $config['database']['dbname'] . ';charset=utf8mb4';
    $pdo = new PDO($dsn, $config['database']['user'], $config['database']['password']);
    echo "   ✓ Database connection successful\n\n";
    
    echo "5. Initializing Flight...\n";
    $app = Flight::app();
    echo "   ✓ Flight initialized\n\n";
    
    echo "6. All checks passed! ✓\n";
    
} catch (PDOException $e) {
    echo "   ✗ DATABASE ERROR: " . $e->getMessage() . "\n\n";
    echo "This is likely why you're getting 500 error.\n";
    echo "Check if MySQL is running and credentials are correct.\n";
} catch (Exception $e) {
    echo "   ✗ ERROR: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . ":" . $e->getLine() . "\n\n";
} catch (Error $e) {
    echo "   ✗ FATAL: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . ":" . $e->getLine() . "\n\n";
}

echo "</pre>";
