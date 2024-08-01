<?php

namespace GlobalPrcf\Core;

use GlobalPrcf\Services\AcfImportService;

/**
 * Fired during plugin activation
 *
 * @link       https://totalonion.com
 * @since      1.0.0
 *
 * @package    GlobalPrcf
 * @subpackage GlobalPrcf/Core
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    GlobalPrcf
 * @subpackage GlobalPrcf/Core
 * @author     Johann Biteghe <johann@totalonion.com>
 */
class Activator {

	/**
     * @var array
     */
    const DEPENDENCIES = [];

    public static function activate()
    {
        Activator::checkRequiredPlugins();
    }

    private static function checkRequiredPlugins()
    {
        $activePlugins = apply_filters('active_plugins', get_option('active_plugins'));
        $missingPlugins = [];

        foreach (Activator::DEPENDENCIES as $requiredPlugin) {
            if (!in_array($requiredPlugin['fingerprint'], $activePlugins)) {
                $missingPlugins[] = $requiredPlugin['friendlyName'].' is required to use this plugin. Please install it before activating global-plugin-events.';
            }
        }

        if (!empty($missingPlugins)) {
            $error = new \WP_Error(
                'broke',
                __(implode('<br />', $missingPlugins), GLOBAL_PRCF_NAME)
            );
            wp_die($error);
        }
    }
}
