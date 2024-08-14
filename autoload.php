<?php

// Load functions
// require_once GLOBAL_CFO_PLUGIN_FOLDER . '/includes/functions.php';

spl_autoload_register(function (string $className) {
    if (strpos($className, GLOBAL_CFO_NAMESPACE) !== 0) {
        return;
    }

    $pathParts = explode('\\', $className);
    $pathParts[0] = 'src';
    
    include GLOBAL_CFO_PLUGIN_FOLDER . '/' . implode('/', $pathParts) . '.php';
});
