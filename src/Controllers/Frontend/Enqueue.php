<?php

namespace GlobalCfo\Controllers\Frontend;

use GlobalCfo\Controllers\AbstractController;

class Enqueue extends AbstractController
{
    public function enqueueScripts()
    {
        wp_enqueue_script(
            $this->pluginName.'_main',
            GLOBAL_CFO_PLUGIN_URL.'src/Resources/js/public.js',
            [],
            $this->version,
            true
        );
        wp_enqueue_script(
            $this->pluginName.'_dist_snapselect_js',
            GLOBAL_CFO_PLUGIN_URL.'dist/js/snapselect.min.js',
            [],
            $this->version,
            true
        );
        wp_enqueue_style(
            $this->pluginName.'_dist_snapselect_css',
            GLOBAL_CFO_PLUGIN_URL.'dist/css/snapselect.min.css',
            [],
            $this->version
        );
    }
}
