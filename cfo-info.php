<?php
define('GLOBAL_CFO_VERSION', '1.1.0');
define('GLOBAL_CFO_NAME', 'global-cfo');
define('GLOBAL_CFO_NAMESPACE', 'GlobalCfo');
define('GLOBAL_CFO_PLUGIN_FOLDER', __DIR__);

if (function_exists('plugin_dir_url')) {
    define('GLOBAL_CFO_PLUGIN_URL', plugin_dir_url(__FILE__));
}
