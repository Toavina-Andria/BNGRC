<?php
echo "<pre>";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'not set') . "\n";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'not set') . "\n";
echo "DOCUMENT_ROOT: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'not set') . "\n";
echo "PHP_SELF: " . ($_SERVER['PHP_SELF'] ?? 'not set') . "\n";

$script_dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
echo "\nscript_dir: " . $script_dir . "\n";

$base_url = '/';
if ($script_dir !== '/') {
    $base_url = rtrim($script_dir, '/') . '/';
}
echo "base_url: " . $base_url . "\n";
echo "</pre>";
