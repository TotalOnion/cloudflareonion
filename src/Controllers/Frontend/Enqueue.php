<?php

namespace GlobalCfo\Controllers\Frontend;

use GlobalCfo\Controllers\AbstractController;

class Enqueue extends AbstractController
{
    public function enqueueScripts()
    {
        wp_enqueue_script(
            $this->pluginName.'_main',
            GLOBAL_CFO_PLUGIN_URL.'dist/public.js',
            [],
            $this->version,
            true
        );
        wp_enqueue_script(
            $this->pluginName.'_dist_snapselect_js',
            GLOBAL_CFO_PLUGIN_URL.'dist/snapselect/snapselect.min.js',
            [],
            $this->version,
            true
        );
        wp_enqueue_style(
            $this->pluginName.'_dist_snapselect_css',
            GLOBAL_CFO_PLUGIN_URL.'dist/snapselect/snapselect.min.css',
            [],
            $this->version
        );
        wp_enqueue_script(
            $this->pluginName.'_nonce',
            GLOBAL_CFO_PLUGIN_URL.'dist/user-config.js',
            [],
            $this->version,
            true
        );
        wp_localize_script($this->pluginName.'_nonce', 'myUserData', array(
            'nonce' => wp_create_nonce('wp_rest'),
            'id' => get_current_user_id(),
        ));
    }
}
