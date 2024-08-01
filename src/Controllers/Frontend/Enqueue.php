<?php

namespace GlobalPrcf\Controllers\Frontend;

use GlobalPrcf\Controllers\AbstractController;

class Enqueue extends AbstractController
{
    public function enqueueScripts()
    {
        wp_enqueue_script(
            $this->pluginName.'_main',
            GLOBAL_PRCF_PLUGIN_URL.'src/Resources/js/public.js',
            [],
            $this->version,
            true
        );
    }
}
