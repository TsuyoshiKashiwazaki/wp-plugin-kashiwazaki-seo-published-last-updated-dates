<?php
if (!defined('ABSPATH')) {
    exit;
}

class KSPLUD_Display {

    private static $instance = null;
    private $settings;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->settings = KSPLUD_Settings::get_instance();
        $this->init_hooks();
    }

    private function init_hooks() {
        add_filter('the_content', array($this, 'add_dates_to_content'), 10);
        add_action('wp_head', array($this, 'output_custom_css'));
        add_action('template_redirect', array($this, 'add_last_modified_header'));
    }

    public function add_dates_to_content($content) {
        if (!is_singular() || !in_the_loop() || !is_main_query()) {
            return $content;
        }

        $post_type = get_post_type();
        if (!$this->settings->is_enabled_for_post_type($post_type)) {
            return $content;
        }

        $dates_html = $this->get_dates_html();
        $position = $this->settings->get_display_position();

        switch ($position) {
            case 'before':
                return $dates_html . $content;
            case 'after':
                return $content . $dates_html;
            case 'both':
                return $dates_html . $content . $dates_html;
            default:
                return $content;
        }
    }

    public function get_dates_html($post_id = null) {
        if (null === $post_id) {
            $post_id = get_the_ID();
        }

        $published_date = get_the_date($this->settings->get_date_format(), $post_id);
        $modified_date = get_the_modified_date($this->settings->get_date_format(), $post_id);

        $published_time = get_the_date('U', $post_id);
        $modified_time = get_the_modified_date('U', $post_id);

        $current_post_type = get_post_type($post_id);
        $show_published = $this->settings->should_show_published_for_post_type($current_post_type);
        $show_updated = $this->settings->should_show_updated_for_post_type($current_post_type);
        $hide_if_not_modified = $this->settings->get_option('hide_if_not_modified', true);
        $threshold = $this->settings->get_option('modified_threshold', 86400);

        // 更新日の表示条件を判定
        $should_show_updated = $show_updated && $modified_date !== $published_date;
        if ($hide_if_not_modified && ($modified_time - $published_time) < $threshold) {
            $should_show_updated = false;
        }

        // 何も表示されない場合のフォールバック処理
        $use_published_fallback = false;
        if (!$show_published && !$should_show_updated) {
            $use_published_fallback = true;
        }

                $design_pattern = $this->settings->get_option('design_pattern', 'badge');

        // 両方の日付が表示されるかチェック
        $both_dates_visible = ($show_published || $use_published_fallback) && $should_show_updated;
        $both_dates_class = $both_dates_visible ? ' ksplud-both-dates' : '';

        // 日付情報のコンテナ（Article全体ではなく日付部分のみ）
        $html = '<div class="ksplud-dates-wrapper ksplud-pattern-' . esc_attr($design_pattern) . esc_attr($both_dates_class) . '">';

        // 更新日の表示（優先表示）
        if ($should_show_updated) {
            $html .= $this->get_single_date_html('updated', $modified_date, $modified_time, $both_dates_visible);
        }

        // 公開日の表示（通常時またはフォールバック時）
        if ($show_published || $use_published_fallback) {
            // フォールバック時は更新日ラベルを使用
            $date_type = $use_published_fallback ? 'updated' : 'published';
            $html .= $this->get_single_date_html($date_type, $published_date, $published_time, $both_dates_visible);
        }

        $html .= '</div>';

        return $html;
    }

    public function get_single_date_html($type, $date, $timestamp, $both_dates_visible = false) {
        $style = $this->settings->get_option('display_style', 'icon_text');
        $text = $type === 'published'
            ? $this->settings->get_option('published_text', '公開日')
            : $this->settings->get_option('updated_text', '更新日');

        $icon = $this->get_icon($type);
        $datetime = date('c', $timestamp);

        // カスタム要素のタグ名を決定
        $custom_tag = $type === 'published' ? 'published-date' : 'updated-date';

        // microdata用の属性
        $schema_prop = $type === 'published' ? 'datePublished' : 'dateModified';
        $microdata_attrs = 'itemprop="' . esc_attr($schema_prop) . '" itemscope itemtype="https://schema.org/DateTime"';

                // カラー設定を取得してインラインスタイルで確実に適用
        // 統一されたdate_colorを使用し、公開日は両方表示時のみ薄く表示
        $base_color = $this->settings->get_option('date_color', '#0ea5e9');

        if ($type === 'published' && $both_dates_visible) {
            // 公開日（両方表示時）は薄く表示
            $color = $base_color;
            $opacity = '0.5';
        } else {
            // 更新日または公開日単独表示時は通常の色
            $color = $base_color;
            $opacity = '1';
        }

        // フォントウェイト：両方の日付が表示される場合は更新日を強調
        if ($type === 'updated' && $both_dates_visible) {
            $font_weight = '700';  // 両方表示時の更新日は特に太く
        } elseif ($type === 'updated') {
            $font_weight = '600';  // 更新日のみの場合
        } else {
            $font_weight = '500';  // 公開日
        }

        $inline_style = 'style="color: ' . esc_attr($color) . ' !important; font-weight: ' . $font_weight . ' !important; opacity: ' . $opacity . ';"';

        $html = '<div class="ksplud-date ksplud-date-' . esc_attr($type) . '">';

        switch ($style) {
            case 'icon_only':
                $html .= '<span class="ksplud-icon" title="' . esc_attr($text) . '">' . $icon . '</span>';
                $html .= '<' . $custom_tag . ' datetime="' . esc_attr($datetime) . '" ' . $microdata_attrs . ' ' . $inline_style . '>' . esc_html($date) . '</' . $custom_tag . '>';
                break;
            case 'text_only':
                $html .= '<span class="ksplud-label">' . esc_html($text) . ':</span>';
                $html .= '<' . $custom_tag . ' datetime="' . esc_attr($datetime) . '" ' . $microdata_attrs . ' ' . $inline_style . '>' . esc_html($date) . '</' . $custom_tag . '>';
                break;
            case 'icon_text':
            default:
                $html .= '<span class="ksplud-icon">' . $icon . '</span>';
                $html .= '<span class="ksplud-label">' . esc_html($text) . ':</span>';
                $html .= '<' . $custom_tag . ' datetime="' . esc_attr($datetime) . '" ' . $microdata_attrs . ' ' . $inline_style . '>' . esc_html($date) . '</' . $custom_tag . '>';
                break;
        }

        $html .= '</div>';

        return $html;
    }

    public function get_icon($type) {
        $icon_style = $this->settings->get_option('icon_style', 'default');

        if ($icon_style === 'none') {
            return '';
        }

        if ($type === 'published') {
            return '<svg class="ksplud-icon-svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect x="3" y="4" width="18" height="18" rx="2"></rect><path d="M3 10h18"></path><path d="M10 16l2 2 4-4"></path></svg>';
        } else {
            return '<svg class="ksplud-icon-svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4Z"></path></svg>';
        }
    }

                public function output_custom_css() {
        $custom_css = $this->settings->get_option('custom_css', '');
        $date_color = $this->settings->get_option('date_color', '#0ea5e9');
        $design_pattern = $this->settings->get_option('design_pattern', 'badge');

        echo '<style type="text/css">' . "\n";

        // CSS変数の定義
        echo ':root {' . "\n";
        echo '  --ksplud-date-color: ' . esc_attr($date_color) . ';' . "\n";
        echo '}' . "\n\n";

        // 統一されたカラー設定 - シンプルな適用
        echo '/* KSPLUD Unified Color Settings */' . "\n";

        // 全ての要素に統一されたカラーを適用
        echo '.ksplud-date .ksplud-icon-svg { stroke: ' . esc_attr($date_color) . ' !important; }' . "\n";
        echo '.ksplud-date .ksplud-label { color: ' . esc_attr($date_color) . ' !important; }' . "\n";

        // カスタム要素用のフォールバック（インラインスタイルが優先されるため）
        echo 'updated-date, published-date { color: ' . esc_attr($date_color) . ' !important; }' . "\n";
        echo '.ksplud-updated-date-fallback, .ksplud-published-date-fallback { color: ' . esc_attr($date_color) . ' !important; }' . "\n\n";

        // 公開日（両方表示時）の薄い表示 - CSSでもフォールバック
        echo '/* 公開日の薄い表示（両方表示時） */' . "\n";
        echo '.ksplud-dates-wrapper.ksplud-both-dates .ksplud-date-published { opacity: 0.5; }' . "\n\n";

        // ボックスパターン用の追加カラー設定
        if ($design_pattern === 'box') {
            echo '/* Box Pattern Colors */' . "\n";
            echo '.ksplud-pattern-box .ksplud-date { border-color: ' . esc_attr($date_color) . ' !important; }' . "\n\n";
        }

        // カスタムCSS
        if (!empty($custom_css)) {
            echo '/* Custom CSS */' . "\n";
            echo esc_html($custom_css) . "\n";
        }

        echo '</style>' . "\n";
    }

    public function add_last_modified_header() {
        if (!is_singular()) {
            return;
        }

        $post_type = get_post_type();
        if (!$this->settings->is_enabled_for_post_type($post_type)) {
            return;
        }

        $post_id = get_the_ID();
        $modified_time = get_the_modified_date('U', $post_id);

        if ($modified_time) {
            $last_modified = gmdate('D, d M Y H:i:s', $modified_time) . ' GMT';
            header('Last-Modified: ' . $last_modified);
        }
    }

    public static function get_published_date($post_id = null, $format = null) {
        if (null === $post_id) {
            $post_id = get_the_ID();
        }

        if (null === $format) {
            $instance = self::get_instance();
            $format = $instance->settings->get_date_format();
        }

        return get_the_date($format, $post_id);
    }

    public static function get_updated_date($post_id = null, $format = null) {
        if (null === $post_id) {
            $post_id = get_the_ID();
        }

        if (null === $format) {
            $instance = self::get_instance();
            $format = $instance->settings->get_date_format();
        }

        return get_the_modified_date($format, $post_id);
    }

    public static function display_published_date($post_id = null, $echo = true) {
        if (null === $post_id) {
            $post_id = get_the_ID();
        }

        $instance = self::get_instance();
        $published_date = get_the_date($instance->settings->get_date_format(), $post_id);
        $published_time = get_the_date('U', $post_id);

        $html = $instance->get_single_date_html('published', $published_date, $published_time, false);

        if ($echo) {
            echo $html;
        } else {
            return $html;
        }
    }

    public static function display_updated_date($post_id = null, $echo = true) {
        if (null === $post_id) {
            $post_id = get_the_ID();
        }

        $instance = self::get_instance();
        $modified_date = get_the_modified_date($instance->settings->get_date_format(), $post_id);
        $modified_time = get_the_modified_date('U', $post_id);

        $current_post_type = get_post_type($post_id);
        $show_updated = $instance->settings->should_show_updated_for_post_type($current_post_type);
        $hide_if_not_modified = $instance->settings->get_option('hide_if_not_modified', true);
        $threshold = $instance->settings->get_option('modified_threshold', 86400);

        if ($hide_if_not_modified) {
            $published_time = get_the_date('U', $post_id);
            if (($modified_time - $published_time) < $threshold) {
                if ($echo) {
                    return;
                } else {
                    return '';
                }
            }
        }

        if (!$show_updated) {
            if ($echo) {
                return;
            } else {
                return '';
            }
        }

        $html = $instance->get_single_date_html('updated', $modified_date, $modified_time, false);

        if ($echo) {
            echo $html;
        } else {
            return $html;
        }
    }

    public static function display_both_dates($post_id = null, $echo = true) {
        if (null === $post_id) {
            $post_id = get_the_ID();
        }

        $instance = self::get_instance();
        $html = $instance->get_dates_html($post_id);

        if ($echo) {
            echo $html;
        } else {
            return $html;
        }
    }
}
