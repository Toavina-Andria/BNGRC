<?php
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$basePath = dirname($scriptName);
if ($basePath === '/' || $basePath === '\\') {
    $basePath = '';
}

echo "<h3>URL Debug Info</h3>";
echo "<pre>";
echo "SCRIPT_NAME: $scriptName\n";
echo "basePath: $basePath\n";
echo "\nGenerated CSS URL:\n";
echo $basePath . "/assets/css/styles.min.css\n";
echo "\nThis should map to:\n";
echo "public/assets/css/styles.min.css\n";
echo "</pre>";

echo "<h3>Test CSS Link</h3>";
echo '<link rel="stylesheet" href="' . $basePath . '/assets/css/styles.min.css" />';
echo '<p>Check browser console/network tab to see if CSS loads</p>';

echo "<h3>Direct Link Test</h3>";
echo '<a href="' . $basePath . '/assets/css/styles.min.css" target="_blank">Click to test CSS file</a>';
