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
    }
}
