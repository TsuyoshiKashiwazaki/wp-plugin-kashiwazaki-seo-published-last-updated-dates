<?php
if (!defined('ABSPATH')) {
    exit;
}

class KSPLUD_Settings {

    private static $instance = null;
    private $options;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->options = get_option('ksplud_settings', $this->get_default_options());
    }

    public function get_default_options() {
        return array(
            'display_position' => 'both',
            'post_types' => array('post'),
            'display_style' => 'icon_text',
            'enable_schema' => true,
            'date_format' => 'Y年n月j日',
            'show_time' => false,
            'custom_css' => '',
            'published_text' => '公開日',
            'updated_text' => '更新日',
            'icon_style' => 'default',
            'show_published' => true,
            'show_updated' => true,
            'hide_if_not_modified' => true,
            'modified_threshold' => 86400,
            // カラー設定
            'date_color' => '#0ea5e9',
            // デザインパターン設定
            'design_pattern' => 'badge',
            // 投稿タイプ別設定
            'post_type_settings' => array(
                'post' => array(
                    'show_published' => true,
                    'show_updated' => true
                ),
                'page' => array(
                    'show_published' => true,
                    'show_updated' => true
                )
            )
        );
    }

    public function get_option($key = null, $default = null) {
        if (null === $key) {
            return $this->options;
        }

        return isset($this->options[$key]) ? $this->options[$key] : $default;
    }

    public function update_option($key, $value) {
        $this->options[$key] = $value;
        return update_option('ksplud_settings', $this->options);
    }

    public function update_options($options) {
        $this->options = array_merge($this->options, $options);
        return update_option('ksplud_settings', $this->options);
    }

    public function get_enabled_post_types() {
        $post_types = $this->get_option('post_types', array('post'));
        return is_array($post_types) ? $post_types : array('post');
    }

    public function is_enabled_for_post_type($post_type) {
        return in_array($post_type, $this->get_enabled_post_types());
    }

    public function get_display_position() {
        return $this->get_option('display_position', 'both');
    }

    public function should_display_schema() {
        return (bool) $this->get_option('enable_schema', true);
    }

    public function get_date_format() {
        $format = $this->get_option('date_format', 'Y年n月j日');
        if ($this->get_option('show_time', false)) {
            $format .= ' H:i';
        }
        return $format;
    }

    public function get_post_type_setting($post_type, $setting_key, $default = null) {
        $post_type_settings = $this->get_option('post_type_settings', array());

        if (isset($post_type_settings[$post_type][$setting_key])) {
            return $post_type_settings[$post_type][$setting_key];
        }

        // フォールバック: グローバル設定を確認
        if ($setting_key === 'show_published' || $setting_key === 'show_updated') {
            return $this->get_option($setting_key, $default !== null ? $default : true);
        }

        return $default;
    }

    public function should_show_published_for_post_type($post_type) {
        return (bool) $this->get_post_type_setting($post_type, 'show_published', true);
    }

    public function should_show_updated_for_post_type($post_type) {
        return (bool) $this->get_post_type_setting($post_type, 'show_updated', true);
    }

    public function get_all_post_types_for_settings() {
        $post_types = get_post_types(array('public' => true), 'objects');
        $enabled_post_types = $this->get_enabled_post_types();
        $filtered_post_types = array();

        foreach ($post_types as $post_type) {
            if ($post_type->name !== 'attachment' && in_array($post_type->name, $enabled_post_types)) {
                $filtered_post_types[$post_type->name] = $post_type;
            }
        }

        return $filtered_post_types;
    }
}
