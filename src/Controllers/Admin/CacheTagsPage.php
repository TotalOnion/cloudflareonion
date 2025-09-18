<?php

namespace GlobalCfo\Controllers\Admin;

use GlobalCfo\Controllers\AbstractController;

class CacheTagsPage extends AbstractController
{
    public function registerPage()
    {
        // reference https://developer.wordpress.org/reference/functions/add_options_page/
        add_options_page(
            'CFO Cache Tags Settings',                     // page title
            'CFO Cache Tags Settings',                     // menu title
            'manage_options',                   // capability required to access / see it
            GLOBAL_CFO_NAME.'_cache-tags-page', // slug (needs to be unique)
            [$this, 'renderPage']               // callable function to render the page
        );
    }

    private function registerCacheTagsFields()
    {
        add_settings_section(
            GLOBAL_CFO_NAME.'_options_section_tags',
            'Cache Tags Settings',
            [$this, 'renderSectionIntro'],
            GLOBAL_CFO_NAME.'_cache-tags-page'
        );

        add_option(GLOBAL_CFO_NAME.'_enableCacheTags');
        register_setting(
            GLOBAL_CFO_NAME.'_tags_options',
            GLOBAL_CFO_NAME.'_enableCacheTags',
            [
                'type' => 'number',
                'description' => 'Whether or not to enable Cache Tags based purging',
                'sanitize_callback' => 'sanitize_text_field',
                'show_in_rest' => true,
                'default' => ''
            ]
        );
        add_settings_field(
            GLOBAL_CFO_NAME.'_enableCacheTags',
            'Enable Tags based purging',
            [$this, 'renderField'],
            GLOBAL_CFO_NAME.'_cache-tags-page',
            GLOBAL_CFO_NAME.'_options_section_tags',
            [
                'id' => GLOBAL_CFO_NAME.'_enableCacheTags',
                'type' => 'checkbox'
            ]
        );

        add_option(GLOBAL_CFO_NAME.'_customCPTTags');
        register_setting(
            GLOBAL_CFO_NAME.'_tags_options',
            GLOBAL_CFO_NAME.'_customCPTTags',
            [
                'type' => 'text',
                'description' => 'Custom tag to purge when a specific post type is saved',
                'sanitize_callback' => 'sanitize_text_field',
                'show_in_rest' => true,
                'default' => ''
            ]
        );
        add_settings_field(
            GLOBAL_CFO_NAME.'_customCPTTags',
            'Tags to purge by PostType',
            [$this, 'renderField'],
            GLOBAL_CFO_NAME.'_cache-tags-page',
            GLOBAL_CFO_NAME.'_options_section_tags',
            [
                'id' => GLOBAL_CFO_NAME.'_customCPTTags',
                'type' => 'CPTTable',
                'CPTs' => get_option(GLOBAL_CFO_NAME.'_purgePostTypes', '')
            ]
        );
    }

    public function registerSettings()
    {
        $this->registerCacheTagsFields();
    }

    public function renderSectionIntro()
    {
        echo __('', GLOBAL_CFO_NAME);
    }

    public function renderField($fieldParameters)
    {
        echo $this->render(
            'forms:fields/'.$fieldParameters['type'].'.php',
            [
                'id' => $fieldParameters['id'],
                'name' => $fieldParameters['id'],
                'currentValue' => get_option($fieldParameters['id']) ?? '',
                'cssClass' => $fieldParameters['cssClass'] ?? '',
                'choices' => $fieldParameters['choices'] ?? '',
                'CPTs' => $fieldParameters['CPTs'] ?? ''
            ]
        );
    }

    public function renderPage()
    {
        echo $this->render('forms:settingsPage.php');
    }
}
