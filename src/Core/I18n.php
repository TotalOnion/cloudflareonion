<?php

namespace GlobalCfo\Core;

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://totalonion.com
 * @since      1.0.0
 *
 * @package    GlobalCfo
 * @subpackage GlobalCfo/Core
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    GlobalCfo
 * @subpackage GlobalCfo/Core
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
            'global-cfo',
            false,
            dirname(GLOBAL_CFO_PLUGIN_FOLDER . '/languages/')
        );
    }
}
