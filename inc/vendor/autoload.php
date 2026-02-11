<?php
/**
 * Simple autoloader for locally included dependencies
 * This replaces Composer's autoloader for the minimal dependencies needed
 */

// Define base path
$vendorDir = __DIR__;

// Autoloader for all locally included libraries
spl_autoload_register(function ($class) use ($vendorDir) {
    // Map of namespace prefixes to directory paths
    $prefixMap = [
        'Dotenv\\' => $vendorDir . '/vlucas/phpdotenv/',
        'PhpOption\\' => $vendorDir . '/phpoption/phpoption/',
        'GrahamCampbell\\ResultType\\' => $vendorDir . '/graham-campbell/result-type/',
    ];
    
    // Check each namespace prefix
    foreach ($prefixMap as $prefix => $baseDir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            // This prefix doesn't match, try next one
            continue;
        }
        
        // Get the relative class name
        $relativeClass = substr($class, $len);
        
        // Replace namespace separators with directory separators
        $file = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';
        
        // If the file exists, require it
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }
    
    return false;
});

// Load classes from inc/classes directory (original composer autoload classmap)
$classesDir = dirname($vendorDir) . '/classes/';
if (is_dir($classesDir)) {
    foreach (glob($classesDir . '*.php') as $file) {
        require_once $file;
    }
}
