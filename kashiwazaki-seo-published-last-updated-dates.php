<?php
/**
 * Plugin Name: Kashiwazaki SEO Published & Last Updated Dates
 * Plugin URI: https://tsuyoshikashiwazaki.jp
 * Description: 記事の公開日と更新日を表示するSEO対策プラグイン
 * Version: 1.0.1
 * Author: 柏崎 剛
 * Author URI: https://tsuyoshikashiwazaki.jp
 * License: GPL-2.0+
 * Text Domain: kashiwazaki-seo-published-last-updated-dates
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

define('KSPLUD_VERSION', '1.0.1');
define('KSPLUD_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('KSPLUD_PLUGIN_URL', plugin_dir_url(__FILE__));
define('KSPLUD_PLUGIN_BASENAME', plugin_basename(__FILE__));

require_once KSPLUD_PLUGIN_DIR . 'includes/class-settings.php';
require_once KSPLUD_PLUGIN_DIR . 'includes/class-display.php';
require_once KSPLUD_PLUGIN_DIR . 'includes/class-shortcode.php';
require_once KSPLUD_PLUGIN_DIR . 'includes/class-schema.php';

if (is_admin()) {
    require_once KSPLUD_PLUGIN_DIR . 'admin/class-admin.php';
}

class Kashiwazaki_SEO_Published_Last_Updated_Dates {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->init_hooks();
    }

    private function init_hooks() {
        add_action('init', array($this, 'load_textdomain'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_public_assets'));

        KSPLUD_Settings::get_instance();
        KSPLUD_Display::get_instance();
        KSPLUD_Shortcode::get_instance();
        KSPLUD_Schema::get_instance();

        if (is_admin()) {
            KSPLUD_Admin::get_instance();
        }
    }

    public function load_textdomain() {
        load_plugin_textdomain(
            'kashiwazaki-seo-published-last-updated-dates',
            false,
            dirname(KSPLUD_PLUGIN_BASENAME) . '/languages'
        );
    }

    public function enqueue_public_assets() {
        wp_enqueue_style(
            'ksplud-public-style',
            KSPLUD_PLUGIN_URL . 'public/css/public-style.css',
            array(),
            KSPLUD_VERSION
        );

        // カスタム要素のJavaScriptを読み込み
        wp_enqueue_script(
            'ksplud-custom-elements',
            KSPLUD_PLUGIN_URL . 'public/js/custom-elements.js',
            array(),
            KSPLUD_VERSION,
            true
        );
    }
}

function ksplud_init() {
    return Kashiwazaki_SEO_Published_Last_Updated_Dates::get_instance();
}

add_action('plugins_loaded', 'ksplud_init');

register_activation_hook(__FILE__, 'ksplud_activate');
register_deactivation_hook(__FILE__, 'ksplud_deactivate');

function ksplud_activate() {
    $default_options = array(
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

    if (!get_option('ksplud_settings')) {
        add_option('ksplud_settings', $default_options);
    }
}

function ksplud_deactivate() {
}
