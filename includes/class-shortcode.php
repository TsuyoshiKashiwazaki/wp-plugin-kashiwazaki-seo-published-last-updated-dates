<?php
if (!defined('ABSPATH')) {
    exit;
}

class KSPLUD_Shortcode {

    private static $instance = null;
    private $display;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->display = KSPLUD_Display::get_instance();
        $this->init_shortcodes();
    }

    private function init_shortcodes() {
        add_shortcode('published_date', array($this, 'published_date_shortcode'));
        add_shortcode('updated_date', array($this, 'updated_date_shortcode'));
        add_shortcode('publish_update_dates', array($this, 'both_dates_shortcode'));
    }

    public function published_date_shortcode($atts) {
        $atts = shortcode_atts(array(
            'format' => '',
            'icon' => 'true',
            'label' => '',
            'class' => ''
        ), $atts, 'published_date');

        $post_id = get_the_ID();
        if (!$post_id) {
            return '';
        }

        $settings = KSPLUD_Settings::get_instance();
        $format = !empty($atts['format']) ? $atts['format'] : $settings->get_date_format();
        $date = get_the_date($format, $post_id);
        $timestamp = get_the_date('U', $post_id);
        $datetime = date('c', $timestamp);

        $label = !empty($atts['label']) ? $atts['label'] : $settings->get_option('published_text', '公開日');
        $show_icon = filter_var($atts['icon'], FILTER_VALIDATE_BOOLEAN);

        $html = '<span class="ksplud-shortcode-date ksplud-published ' . esc_attr($atts['class']) . '">';

        if ($show_icon) {
            $html .= '<span class="ksplud-icon">' . $this->get_published_icon() . '</span>';
        }

        if (!empty($label)) {
            $html .= '<span class="ksplud-label">' . esc_html($label) . ':</span>';
        }

        $html .= '<time datetime="' . esc_attr($datetime) . '">' . esc_html($date) . '</time>';
        $html .= '</span>';

        return $html;
    }

    public function updated_date_shortcode($atts) {
        $atts = shortcode_atts(array(
            'format' => '',
            'icon' => 'true',
            'label' => '',
            'class' => '',
            'hide_if_not_modified' => 'true'
        ), $atts, 'updated_date');

        $post_id = get_the_ID();
        if (!$post_id) {
            return '';
        }

        $settings = KSPLUD_Settings::get_instance();

        if (filter_var($atts['hide_if_not_modified'], FILTER_VALIDATE_BOOLEAN)) {
            $published_time = get_the_date('U', $post_id);
            $modified_time = get_the_modified_date('U', $post_id);
            $threshold = $settings->get_option('modified_threshold', 86400);

            if (($modified_time - $published_time) < $threshold) {
                return '';
            }
        }

        $format = !empty($atts['format']) ? $atts['format'] : $settings->get_date_format();
        $date = get_the_modified_date($format, $post_id);
        $timestamp = get_the_modified_date('U', $post_id);
        $datetime = date('c', $timestamp);

        $label = !empty($atts['label']) ? $atts['label'] : $settings->get_option('updated_text', '更新日');
        $show_icon = filter_var($atts['icon'], FILTER_VALIDATE_BOOLEAN);

        $html = '<span class="ksplud-shortcode-date ksplud-updated ' . esc_attr($atts['class']) . '">';

        if ($show_icon) {
            $html .= '<span class="ksplud-icon">' . $this->get_updated_icon() . '</span>';
        }

        if (!empty($label)) {
            $html .= '<span class="ksplud-label">' . esc_html($label) . ':</span>';
        }

        $html .= '<time datetime="' . esc_attr($datetime) . '">' . esc_html($date) . '</time>';
        $html .= '</span>';

        return $html;
    }

    public function both_dates_shortcode($atts) {
        $atts = shortcode_atts(array(
            'separator' => ' | ',
            'wrapper_class' => ''
        ), $atts, 'publish_update_dates');

        $post_id = get_the_ID();
        if (!$post_id) {
            return '';
        }

        $html = '<span class="ksplud-shortcode-both-dates ' . esc_attr($atts['wrapper_class']) . '">';
        $updated_html = $this->updated_date_shortcode(array());
        $published_html = $this->published_date_shortcode(array());

        // 更新日を優先表示、なければ公開日のみ
        if (!empty($updated_html)) {
            $html .= $updated_html;
            if (!empty($published_html)) {
                $html .= '<span class="ksplud-separator">' . esc_html($atts['separator']) . '</span>';
                $html .= $published_html;
            }
        } else {
            $html .= $published_html;
        }
        $html .= '</span>';

        return $html;
    }

    private function get_published_icon() {
        return '<svg class="ksplud-icon-svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect x="3" y="4" width="18" height="18" rx="2"></rect><path d="M3 10h18"></path><path d="M10 16l2 2 4-4"></path></svg>';
    }

    private function get_updated_icon() {
        return '<svg class="ksplud-icon-svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4Z"></path></svg>';
    }
}
