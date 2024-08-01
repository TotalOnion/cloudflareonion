<?php

namespace GlobalPrcf\Core;

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://totalonion.com
 * @since      1.0.0
 *
 * @package    GlobalPrcf
 * @subpackage GlobalPrcf/Core
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    GlobalPrcf
 * @subpackage GlobalPrcf/Core
 * @author     Johann Biteghe <johann@totalonion.com>
 */
class I18n
{
    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function loadPluginTextdomain()
    {
        load_plugin_textdomain(
            'global-prcf',
            false,
            dirname(GLOBAL_PRCF_PLUGIN_FOLDER . '/languages/')
        );
    }
}
