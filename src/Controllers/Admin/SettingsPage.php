<?php

namespace GlobalPrcf\Controllers\Admin;

use GlobalPrcf\Controllers\AbstractController;

class SettingsPage extends AbstractController
{
    public function registerPage()
    {
        // reference https://developer.wordpress.org/reference/functions/add_options_page/
        add_options_page(
            'PRCF Settings',                     // page title
            'PRCF Settings',                     // menu title
            'manage_options',                   // capability required to access / see it
            GLOBAL_PRCF_NAME.'settings-page', // slug (needs to be unique)
            [$this, 'renderPage']               // callable function to render the page
        );
    }

    public function registerSettings()
    {
        // This created the option in the wp_option table
        // reference https://developer.wordpress.org/reference/functions/add_option/
        add_option(GLOBAL_PRCF_NAME.'_enableCF');
        add_option(GLOBAL_PRCF_NAME.'_tokenCF');
        add_option(GLOBAL_PRCF_NAME.'_zoneID');

        // This marks them as a setting you can edit in the admin
        // reference https://developer.wordpress.org/reference/functions/register_setting/
        register_setting(
            GLOBAL_PRCF_NAME.'_options',
            GLOBAL_PRCF_NAME.'_enableCF',
            [
                'type' => 'number',
                'description' => 'Whether or not to enable CF',
                'sanitize_callback' => 'sanitize_text_field',
                'show_in_rest' => true,
                'default' => ''
            ]
        );
        register_setting(
            GLOBAL_PRCF_NAME.'_options',
            GLOBAL_PRCF_NAME.'_tokenCF',
            [
                'type' => 'text',
                'description' => 'CF token for API calls',
                'sanitize_callback' => 'sanitize_text_field',
                'show_in_rest' => true,
                'default' => ''
            ]
        );
        register_setting(
            GLOBAL_PRCF_NAME.'_options',
            GLOBAL_PRCF_NAME.'_zoneID',
            [
                'type' => 'text',
                'description' => 'zone ID',
                'sanitize_callback' => 'sanitize_text_field',
                'show_in_rest' => true,
                'default' => ''
            ]
        );

        // Adds the settings *section*
        // reference https://developer.wordpress.org/reference/functions/add_settings_section/
        add_settings_section(
            GLOBAL_PRCF_NAME.'_options_section',  // Unique ID for the section
            'PRCF Plugin settings',      // Title for the section
            [$this, 'renderSectionIntro'],   // Callable function to echo the intro
            GLOBAL_PRCF_NAME.'settings-page'      // the page this section appears on (defined in registerPage above)
        );

        // This adds the html field that renders the setting
        // reference https://developer.wordpress.org/reference/functions/add_settings_field/
        add_settings_field(
            GLOBAL_PRCF_NAME.'_enableCF',   // id="" value
            'Whether to enable CF',          // <label> vale
            [$this, 'renderField'],          // callback to actually do the rendering of the input
            GLOBAL_PRCF_NAME.'settings-page',     // Slug of the page to show this on (defined in registerPage above)
            GLOBAL_PRCF_NAME.'_options_section',  // slug of the section the field appears in
            [                                       // array of values to pass to the render callback
                'id' => GLOBAL_PRCF_NAME.'_enableCF',
                'type' => 'checkbox'
            ]
        );
        add_settings_field(
            GLOBAL_PRCF_NAME.'_tokenCF',   // id="" value
            'CF token api',          // <label> vale
            [$this, 'renderField'],          // callback to actually do the rendering of the input
            GLOBAL_PRCF_NAME.'settings-page',     // Slug of the page to show this on (defined in registerPage above)
            GLOBAL_PRCF_NAME.'_options_section',  // slug of the section the field appears in
            [                                       // array of values to pass to the render callback
                'id' => GLOBAL_PRCF_NAME.'_tokenCF',
                'type' => 'text'
            ]
        );
        add_settings_field(
            GLOBAL_PRCF_NAME.'_zoneID',   // id="" value
            'zone ID for API',          // <label> vale
            [$this, 'renderField'],          // callback to actually do the rendering of the input
            GLOBAL_PRCF_NAME.'settings-page',     // Slug of the page to show this on (defined in registerPage above)
            GLOBAL_PRCF_NAME.'_options_section',  // slug of the section the field appears in
            [                                       // array of values to pass to the render callback
                'id' => GLOBAL_PRCF_NAME.'_zoneID',
                'type' => 'text'
            ]
        );

        // AWS Logging Settings Section
        add_settings_section(
            GLOBAL_PRCF_NAME.'_options_section_aws',
            'AWS Logging settings',
            [$this, 'renderSectionIntro'],
            GLOBAL_PRCF_NAME.'settings-page'
        );

        // Option to enable AWS Logging
        add_option(GLOBAL_PRCF_NAME.'_log_aws_enable');
        register_setting(
            GLOBAL_PRCF_NAME.'_options',
            GLOBAL_PRCF_NAME.'_log_aws_enable',
            [
                'type' => 'number',
                'description' => 'Whether or not to enable AWS Logging',
                'sanitize_callback' => 'sanitize_text_field',
                'show_in_rest' => true,
                'default' => ''
            ]
        );
        add_settings_field(
            GLOBAL_PRCF_NAME.'_log_aws_enable',
            'Whether to enable Aws Logging',
            [$this, 'renderField'],
            GLOBAL_PRCF_NAME.'settings-page',
            GLOBAL_PRCF_NAME.'_options_section_aws',
            [
                'id' => GLOBAL_PRCF_NAME.'_log_aws_enable',
                'type' => 'checkbox'
            ]
        );

        // Option to set the AWS Secret Key
        add_option(GLOBAL_PRCF_NAME.'_log_aws_secret');
        register_setting(
            GLOBAL_PRCF_NAME.'_options',
            GLOBAL_PRCF_NAME.'_log_aws_secret',
            [
                'type' => 'text',
                'description' => 'AWS Secret Key',
                'sanitize_callback' => 'sanitize_text_field',
                'show_in_rest' => true,
                'default' => ''
            ]
        );
        add_settings_field(
            GLOBAL_PRCF_NAME.'_log_aws_secret',
            'Aws Secret Key',
            [$this, 'renderField'],
            GLOBAL_PRCF_NAME.'settings-page',
            GLOBAL_PRCF_NAME.'_options_section_aws',
            [
                'id' => GLOBAL_PRCF_NAME.'_log_aws_secret',
                'type' => 'text'
            ]
        );


        // Option to set the AWS Access Key
        add_option(GLOBAL_PRCF_NAME.'_log_aws_access');
        register_setting(
            GLOBAL_PRCF_NAME.'_options',
            GLOBAL_PRCF_NAME.'_log_aws_access',
            [
                'type' => 'text',
                'description' => 'AWS Access Key',
                'sanitize_callback' => 'sanitize_text_field',
                'show_in_rest' => true,
                'default' => ''
            ]
        );
        add_settings_field(
            GLOBAL_PRCF_NAME.'_log_aws_access',
            'AWS Access Key',
            [$this, 'renderField'],
            GLOBAL_PRCF_NAME.'settings-page',
            GLOBAL_PRCF_NAME.'_options_section_aws',
            [
                'id' => GLOBAL_PRCF_NAME.'_log_aws_access',
                'type' => 'text'
            ]
        );

        // Option to set the AWS Region
        add_option(GLOBAL_PRCF_NAME.'_log_aws_region');
        register_setting(
            GLOBAL_PRCF_NAME.'_options',
            GLOBAL_PRCF_NAME.'_log_aws_region',
            [
                'type' => 'text',
                'description' => 'AWS Region',
                'sanitize_callback' => 'sanitize_text_field',
                'show_in_rest' => true,
                'default' => ''
            ]
        );
        add_settings_field(
            GLOBAL_PRCF_NAME.'_log_aws_region',
            'AWS Region',
            [$this, 'renderField'],
            GLOBAL_PRCF_NAME.'settings-page',
            GLOBAL_PRCF_NAME.'_options_section_aws',
            [
                'id' => GLOBAL_PRCF_NAME.'_log_aws_region',
                'type' => 'text'
            ]
        );

        // Option to set the AWS Log Group
        add_option(GLOBAL_PRCF_NAME.'_log_aws_loggroup');
        register_setting(
            GLOBAL_PRCF_NAME.'_options',
            GLOBAL_PRCF_NAME.'_log_aws_loggroup',
            [
                'type' => 'text',
                'description' => 'AWS Log Group',
                'sanitize_callback' => 'sanitize_text_field',
                'show_in_rest' => true,
                'default' => ''
            ]
        );
        add_settings_field(
            GLOBAL_PRCF_NAME.'_log_aws_loggroup',
            'AWS Log Group',
            [$this, 'renderField'],
            GLOBAL_PRCF_NAME.'settings-page',
            GLOBAL_PRCF_NAME.'_options_section_aws',
            [
                'id' => GLOBAL_PRCF_NAME.'_log_aws_loggroup',
                'type' => 'text'
            ]
        );
    }

    public function renderSectionIntro()
    {
        echo __('PRCF Plugin settings :', GLOBAL_PRCF_NAME);
    }

    public function renderField($fieldParameters)
    {
        echo $this->render(
            'forms:fields/'.$fieldParameters['type'].'.php',
            [
                'id' => $fieldParameters['id'],
                'name' => $fieldParameters['id'],
                'currentValue' => get_option($fieldParameters['id']) ?? '',
                'cssClass' => $fieldParameters['cssClass'] ?? ''
            ]
        );
    }

    public function renderPage()
    {
        echo $this->render('forms:settingsPage.php');
    }
}
