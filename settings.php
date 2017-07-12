<?php
if (!class_exists('MasterLink_Plugin_Settings')) {
    class MasterLink_Plugin_Settings
    {

        public function __construct()
        {
            add_action('admin_init', array(
                &$this,
                'admin_init'
            ));
            add_action('admin_menu', array(
                &$this,
                'add_menu'
            ));
        }

        public function admin_init()
        {

            // add your settings section
            add_settings_section('wp_plugin_template-section', __('Master Link Settings'), array(
                &$this,
                'settings_section_master_link_plugin'
            ), 'master_link_plugin');

            // Add URL Slug field
            add_settings_field('master_link_plugin-slug', 'URL Slug', array(
                &$this,
                'settings_field_input_text_slug'
            ), 'master_link_plugin', 'wp_plugin_template-section');

            // Add HUM config
            if(class_exists(Hum) && is_plugin_active("hum/hum.php")) {
              add_settings_field('master_link_plugin-hum', 'HUM Short URL Slug', array(
                  &$this,
                  'settings_field_input_text_hum'
              ), 'master_link_plugin', 'wp_plugin_template-section');
            }

            // Add Use default template
            add_settings_field('master_link_plugin-use_template', 'Use Plugin Template', array(
                &$this,
                'settings_field_input_use_template'
            ), 'master_link_plugin', 'wp_plugin_template-section');

            // register your plugin's settings
            register_setting('master_link_plugin', 'master_link_plugin-slug');
            register_setting('master_link_plugin', 'master_link_plugin-use_template');
        }

        public function settings_section_master_link_plugin()
        {
            echo 'These are the only paramters needed to get the plugin to work';
        }

        public function settings_field_input_text_hum()
        {
            $this->settings_field_input_text(array(
              'field' => 'hum',
              'default' => 'm'
            ));
        }

        public function settings_field_input_text_slug()
        {
            $this->settings_field_input_text(array(
                'field' => 'slug',
                'default' => 'music'
            ));
        }

        public function settings_field_input_text($args)
        {
            // Get the field name from the $args array
            $field = $args['field'];
            $default = $args['default'];

            // Get the value of this setting
            $value = get_option('master_link_plugin-' . $field, $default);

            // echo a proper input type="text"
            echo sprintf('<input type="text" name="master_link_plugin-%s" id="master_link_plugin-%s" value="%s" />', $field, $field, $value);
        }

        public function  settings_field_input_checkbox($args) {
          $field = $args['field'];
          $value = get_option('master_link_plugin-'.$field,1);
          $checked = checked(1, $value, false);
          echo sprintf('<input type="checkbox" id="master_link_plugin-%s" name="master_link_plugin-%s" value="1" %s/>',$field,$field,$checked);
          ?><span class="setting-description"><?php _e("Use the plugin's templating over WordPress defaults (looks for single-master_link.php in your theme and uses plugin styles)"); ?></span><?php
        }

        public function settings_field_input_use_template($args) {
          $this->settings_field_input_checkbox(array(
              'field' => 'use_template',
          ));
        }

        public function add_menu()
        {
            // Add a page to manage this plugin's settings
            add_options_page(__('Master Link Plugin Settings'), __('Master Link Plugin'), 'manage_options', 'master_link_plugin', array(
                &$this,
                'plugin_settings_page'
            ));
        }

        public function plugin_settings_page()
        {
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }

            // Render the settings template
            include(sprintf("%s/templates/settings.php", dirname(__FILE__)));
        }

    }
}
