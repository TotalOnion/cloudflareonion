<?php

namespace GlobalCfo\Controllers\Admin;

use GlobalCfo\Controllers\AbstractController;
use GlobalCfo\Controllers\Admin;

class CacheClearancePage extends AbstractController
{
    private $cfoManager;

    public function __construct()
    {
        $this->cfoManager = new Admin\CfoManager(
            $this->pluginName,
            $this->version
        );
    }

    public function registerPage()
    {
        add_options_page(
            'CFO Cache Clearance',
            'CFO Cache Clearance',
            'manage_options',
            GLOBAL_CFO_NAME . '_cache-clearance-page',
            [$this, 'renderPage']
        );
    }

    private function registerMarketClearingFields()
    {
        add_settings_section(
            GLOBAL_CFO_NAME.'_clear_section',
            '',
            [$this, 'renderMarketClearingSection'],
            GLOBAL_CFO_NAME.'_cache-clearance-page'
        );
    }

    public function registerSettings()
    {
        $this->registerMarketClearingFields();

        // Register section and field for the form structure
        add_settings_section(
            GLOBAL_CFO_NAME . '_path_validator_section',
            '',
            [$this, 'renderSectionIntro'],
            GLOBAL_CFO_NAME . '_cache-clearance-page'
        );

        add_settings_field(
            'url_validator_field',
            '',
            [$this, 'renderField'],
            GLOBAL_CFO_NAME . '_cache-clearance-page',
            GLOBAL_CFO_NAME . '_path_validator_section',
            [
                'id' => GLOBAL_CFO_NAME . '_path-validator',
                // 'name' => GLOBAL_CFO_NAME.'_enableCF',
                'name' => 'field_path_validator',
                'type' => 'textarea',
                'cssClass' => 'large-text',
            ]
        );

        // Register setting but don't save to database
        register_setting(
            GLOBAL_CFO_NAME . '_clearance',
            'field_path_validator',
            [
                'type' => 'text',
                'sanitize_callback' => [$this, 'handleFormSubmission'],
                // 'show_in_rest' => false,
                // 'default' => '',
            ]
        );
    }

    public function renderField($fieldParameters)
    {
        echo $this->render(
            'forms:fields/' . $fieldParameters['type'] . '.php',
            [
                'id' => $fieldParameters['id'],
                'name' => $fieldParameters['name'] ?? $fieldParameters['id'],
                'description' => $fieldParameters['description'] ?? '',
                'currentValue' => get_option($fieldParameters['id']) ?? '',
                'cssClass' => $fieldParameters['cssClass'] ?? ''
            ]
        );
    }

    public function handleFormSubmission($input)
    {
        $paths = array_filter(explode("\n", $input));

        foreach ($paths as $path) {
            $path = trim($path);
            if (empty($path)) continue;

            if (!isValidUrlPath($path)) {
                $error = sprintf(
                    '<div class="notice notice-error"><p>Invalid PATH format: %s</p></div>',
                    esc_html($path)
                );

                add_settings_error(
                    'field_path_validator',
                    'invalid_paths',
                    $error,
                    'error'
                );
            } else {
                add_settings_error(
                    'field_path_validator',
                    'invalid_paths',
                    "Sending '${path}' to Cloudflare for clearance",
                    'warning'
                );

                $url = home_url($path);
                $this->cfoManager->registerSavedItem($url);
            }
        }

        // Return empty string to prevent database saving
        return '';
    }

    public function renderSectionIntro()
    {
        echo $this->render('tools:urlPurge.php');
    }

    public function renderMarketClearingSection()
    {
        echo $this->render('tools:marketPurge.php');
    }

    public function renderPage()
    {
        echo $this->render('forms:settingsPage.php');
    }
}
