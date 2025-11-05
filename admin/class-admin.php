<?php
if (!defined('ABSPATH')) {
    exit;
}

class KSPLUD_Admin {

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
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_filter('plugin_action_links_' . KSPLUD_PLUGIN_BASENAME, array($this, 'add_settings_link'));
    }

    public function add_admin_menu() {
        add_menu_page(
            'Kashiwazaki SEO Published & Last Updated Dates',
            'Kashiwazaki SEO Published & Last Updated Dates',
            'manage_options',
            'ksplud-settings',
            array($this, 'render_settings_page'),
            'dashicons-calendar-alt',
            81
        );
    }

    public function add_settings_link($links) {
        $settings_link = '<a href="' . admin_url('admin.php?page=ksplud-settings') . '">' . __('設定', 'kashiwazaki-seo-published-last-updated-dates') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    public function register_settings() {
        register_setting('ksplud_settings_group', 'ksplud_settings', array($this, 'sanitize_settings'));
    }

                        public function enqueue_admin_assets($hook) {
        // プラグインの設定ページでのみ読み込み
        if (strpos($hook, 'ksplud-settings') === false) {
            return;
        }

        // WordPress カラーピッカーのスタイルとスクリプトを読み込み
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');

        wp_enqueue_style(
            'ksplud-admin-style',
            KSPLUD_PLUGIN_URL . 'admin/css/admin-style.css',
            array('wp-color-picker'),
            KSPLUD_VERSION
        );

        wp_enqueue_script(
            'ksplud-admin-script',
            KSPLUD_PLUGIN_URL . 'admin/js/admin-script.js',
            array('jquery', 'wp-color-picker'),
            KSPLUD_VERSION,
            true
        );

        wp_localize_script('ksplud-admin-script', 'ksplud_admin', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ksplud_admin_nonce')
        ));
    }

    public function render_settings_page() {
        $options = $this->settings->get_option();
        $post_types = get_post_types(array('public' => true), 'objects');
        ?>
        <div class="wrap">
            <h1>Kashiwazaki SEO Published & Last Updated Dates</h1>

            <form method="post" action="options.php">
                <?php settings_fields('ksplud_settings_group'); ?>

                <div class="ksplud-settings-container">
                    <div class="ksplud-settings-main">
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php _e('表示位置', 'kashiwazaki-seo-published-last-updated-dates'); ?></th>
                                <td>
                                    <select name="ksplud_settings[display_position]">
                                        <option value="before" <?php selected($options['display_position'], 'before'); ?>><?php _e('記事の前', 'kashiwazaki-seo-published-last-updated-dates'); ?></option>
                                        <option value="after" <?php selected($options['display_position'], 'after'); ?>><?php _e('記事の後', 'kashiwazaki-seo-published-last-updated-dates'); ?></option>
                                        <option value="both" <?php selected($options['display_position'], 'both'); ?>><?php _e('記事の前後両方', 'kashiwazaki-seo-published-last-updated-dates'); ?></option>
                                        <option value="none" <?php selected($options['display_position'], 'none'); ?>><?php _e('自動表示しない（ショートコードのみ）', 'kashiwazaki-seo-published-last-updated-dates'); ?></option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row"><?php _e('対象投稿タイプ', 'kashiwazaki-seo-published-last-updated-dates'); ?></th>
                                <td>
                                    <?php
                                    $post_type_settings = isset($options['post_type_settings']) ? $options['post_type_settings'] : array();

                                    foreach ($post_types as $post_type) :
                                        if ($post_type->name === 'attachment') continue;

                                        $is_enabled = in_array($post_type->name, $options['post_types']);
                                        $show_published = isset($post_type_settings[$post_type->name]['show_published'])
                                            ? $post_type_settings[$post_type->name]['show_published']
                                            : true;
                                        $show_updated = isset($post_type_settings[$post_type->name]['show_updated'])
                                            ? $post_type_settings[$post_type->name]['show_updated']
                                            : true;
                                    ?>
                                        <div class="ksplud-post-type-item" style="margin-bottom: 15px; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                                            <label style="display: block; margin-bottom: 8px; font-weight: bold;">
                                                <input type="checkbox"
                                                       name="ksplud_settings[post_types][]"
                                                       value="<?php echo esc_attr($post_type->name); ?>"
                                                       class="ksplud-post-type-checkbox"
                                                       data-post-type="<?php echo esc_attr($post_type->name); ?>"
                                                       <?php checked($is_enabled); ?>>
                                                <?php echo esc_html($post_type->label); ?>
                                            </label>
                                            <div class="ksplud-post-type-settings"
                                                 data-post-type="<?php echo esc_attr($post_type->name); ?>"
                                                 style="margin-left: 24px; <?php echo $is_enabled ? '' : 'display: none;'; ?>">
                                                <label style="display: block; margin-bottom: 5px;">
                                                    <input type="checkbox"
                                                           name="ksplud_settings[post_type_settings][<?php echo esc_attr($post_type->name); ?>][show_published]"
                                                           value="1"
                                                           <?php checked($show_published, true); ?>>
                                                    <?php _e('公開日を表示', 'kashiwazaki-seo-published-last-updated-dates'); ?>
                                                </label>
                                                <label style="display: block;">
                                                    <input type="checkbox"
                                                           name="ksplud_settings[post_type_settings][<?php echo esc_attr($post_type->name); ?>][show_updated]"
                                                           value="1"
                                                           <?php checked($show_updated, true); ?>>
                                                    <?php _e('更新日を表示', 'kashiwazaki-seo-published-last-updated-dates'); ?>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                    <p class="description"><?php _e('投稿タイプをチェックすると、そのタイプの詳細設定が表示されます', 'kashiwazaki-seo-published-last-updated-dates'); ?></p>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row"><?php _e('表示スタイル', 'kashiwazaki-seo-published-last-updated-dates'); ?></th>
                                <td>
                                    <select name="ksplud_settings[display_style]">
                                        <option value="icon_text" <?php selected($options['display_style'], 'icon_text'); ?>><?php _e('アイコン + テキスト', 'kashiwazaki-seo-published-last-updated-dates'); ?></option>
                                        <option value="icon_only" <?php selected($options['display_style'], 'icon_only'); ?>><?php _e('アイコンのみ', 'kashiwazaki-seo-published-last-updated-dates'); ?></option>
                                        <option value="text_only" <?php selected($options['display_style'], 'text_only'); ?>><?php _e('テキストのみ', 'kashiwazaki-seo-published-last-updated-dates'); ?></option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row"><?php _e('日付フォーマット', 'kashiwazaki-seo-published-last-updated-dates'); ?></th>
                                <td>
                                    <input type="text" name="ksplud_settings[date_format]"
                                           value="<?php echo esc_attr($options['date_format']); ?>"
                                           class="regular-text">
                                    <p class="description"><?php _e('PHPの日付フォーマットを使用（例：Y年n月j日）', 'kashiwazaki-seo-published-last-updated-dates'); ?></p>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row"><?php _e('時間を表示', 'kashiwazaki-seo-published-last-updated-dates'); ?></th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="ksplud_settings[show_time]" value="1"
                                               <?php checked($options['show_time'], true); ?>>
                                        <?php _e('時間も表示する', 'kashiwazaki-seo-published-last-updated-dates'); ?>
                                    </label>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row"><?php _e('構造化マークアップ', 'kashiwazaki-seo-published-last-updated-dates'); ?></th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="ksplud_settings[enable_schema]" value="1"
                                               <?php checked($options['enable_schema'], true); ?>>
                                        <?php _e('Schema.orgマークアップを出力する', 'kashiwazaki-seo-published-last-updated-dates'); ?>
                                    </label>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row"><?php _e('カラー設定', 'kashiwazaki-seo-published-last-updated-dates'); ?></th>
                                <td>
                                    <div class="ksplud-color-settings">
                                        <label>
                                            <?php _e('日付の色', 'kashiwazaki-seo-published-last-updated-dates'); ?>
                                            <input type="text" name="ksplud_settings[date_color]"
                                                   value="<?php echo esc_attr(isset($options['date_color']) ? $options['date_color'] : '#0ea5e9'); ?>"
                                                   class="color-picker"
                                                   data-default-color="#0ea5e9">
                                        </label>
                                    </div>
                                    <p class="description"><?php _e('アイコンとテキストの色を設定します。両方表示時は公開日が薄く表示されます', 'kashiwazaki-seo-published-last-updated-dates'); ?></p>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row"><?php _e('デザインパターン', 'kashiwazaki-seo-published-last-updated-dates'); ?></th>
                                <td>
                                    <div class="ksplud-design-patterns">
                                        <?php
                                        $current_pattern = isset($options['design_pattern']) ? $options['design_pattern'] : 'badge';
                                        $patterns = array(
                                            'badge' => array(
                                                'name' => __('バッジスタイル', 'kashiwazaki-seo-published-last-updated-dates'),
                                                'description' => __('丸みを帯びた現代的なバッジデザイン', 'kashiwazaki-seo-published-last-updated-dates')
                                            ),
                                            'simple' => array(
                                                'name' => __('シンプルテキスト', 'kashiwazaki-seo-published-last-updated-dates'),
                                                'description' => __('装飾のないシンプルなテキスト表示', 'kashiwazaki-seo-published-last-updated-dates')
                                            ),
                                            'box' => array(
                                                'name' => __('ボックススタイル', 'kashiwazaki-seo-published-last-updated-dates'),
                                                'description' => __('角のあるボックス型のデザイン', 'kashiwazaki-seo-published-last-updated-dates')
                                            ),
                                            'line' => array(
                                                'name' => __('ライン付きスタイル', 'kashiwazaki-seo-published-last-updated-dates'),
                                                'description' => __('左端にカラーラインが入るデザイン', 'kashiwazaki-seo-published-last-updated-dates')
                                            )
                                        );

                                        foreach ($patterns as $pattern_key => $pattern_info) : ?>
                                            <label class="ksplud-pattern-option">
                                                <input type="radio" name="ksplud_settings[design_pattern]"
                                                       value="<?php echo esc_attr($pattern_key); ?>"
                                                       <?php checked($current_pattern, $pattern_key); ?>>
                                                <div class="pattern-preview">
                                                    <strong><?php echo esc_html($pattern_info['name']); ?></strong>
                                                    <p><?php echo esc_html($pattern_info['description']); ?></p>
                                                </div>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                    <p class="description"><?php _e('日付の表示デザインを選択できます', 'kashiwazaki-seo-published-last-updated-dates'); ?></p>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row"><?php _e('ラベルテキスト', 'kashiwazaki-seo-published-last-updated-dates'); ?></th>
                                <td>
                                    <label><?php _e('公開日ラベル:', 'kashiwazaki-seo-published-last-updated-dates'); ?>
                                        <input type="text" name="ksplud_settings[published_text]"
                                               value="<?php echo esc_attr($options['published_text']); ?>"
                                               class="regular-text">
                                    </label><br>
                                    <label><?php _e('更新日ラベル:', 'kashiwazaki-seo-published-last-updated-dates'); ?>
                                        <input type="text" name="ksplud_settings[updated_text]"
                                               value="<?php echo esc_attr($options['updated_text']); ?>"
                                               class="regular-text">
                                    </label>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row"><?php _e('更新日の表示条件', 'kashiwazaki-seo-published-last-updated-dates'); ?></th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="ksplud_settings[hide_if_not_modified]" value="1"
                                               <?php checked($options['hide_if_not_modified'], true); ?>>
                                        <?php _e('公開直後の場合は更新日を表示しない', 'kashiwazaki-seo-published-last-updated-dates'); ?>
                                    </label>
                                    <p class="description"><?php _e('公開から24時間以内の更新は表示しません', 'kashiwazaki-seo-published-last-updated-dates'); ?></p>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row"><?php _e('カスタムCSS', 'kashiwazaki-seo-published-last-updated-dates'); ?></th>
                                <td>
                                    <textarea name="ksplud_settings[custom_css]" rows="10" class="large-text code"><?php echo esc_textarea($options['custom_css']); ?></textarea>
                                    <p class="description"><?php _e('独自のスタイルを追加できます', 'kashiwazaki-seo-published-last-updated-dates'); ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="ksplud-settings-sidebar">
                        <div class="ksplud-info-box">
                            <h3><?php _e('ショートコード', 'kashiwazaki-seo-published-last-updated-dates'); ?></h3>
                            <p><code>[published_date]</code> - <?php _e('公開日を表示', 'kashiwazaki-seo-published-last-updated-dates'); ?></p>
                            <p><code>[updated_date]</code> - <?php _e('更新日を表示', 'kashiwazaki-seo-published-last-updated-dates'); ?></p>
                            <p><code>[publish_update_dates]</code> - <?php _e('両方を表示', 'kashiwazaki-seo-published-last-updated-dates'); ?></p>

                            <h4><?php _e('パラメータ例', 'kashiwazaki-seo-published-last-updated-dates'); ?></h4>
                            <p><code>[published_date format="Y/m/d" icon="false"]</code></p>
                            <p><code>[updated_date label="最終更新" class="my-date"]</code></p>
                        </div>

                        <div class="ksplud-info-box">
                            <h3><?php _e('PHP関数', 'kashiwazaki-seo-published-last-updated-dates'); ?></h3>
                            <p><?php _e('テンプレートファイル（page.php、single.phpなど）で直接使用', 'kashiwazaki-seo-published-last-updated-dates'); ?></p>

                            <h4><?php _e('HTML込みで表示', 'kashiwazaki-seo-published-last-updated-dates'); ?></h4>
                            <p><code>&lt;?php KSPLUD_Display::display_published_date(); ?&gt;</code></p>
                            <p><code>&lt;?php KSPLUD_Display::display_updated_date(); ?&gt;</code></p>
                            <p><code>&lt;?php KSPLUD_Display::display_both_dates(); ?&gt;</code></p>

                            <h4><?php _e('テキストのみ取得', 'kashiwazaki-seo-published-last-updated-dates'); ?></h4>
                            <p><code>&lt;?php echo KSPLUD_Display::get_published_date(); ?&gt;</code></p>
                            <p><code>&lt;?php echo KSPLUD_Display::get_updated_date(); ?&gt;</code></p>

                            <h4><?php _e('パラメータ指定例', 'kashiwazaki-seo-published-last-updated-dates'); ?></h4>
                            <p><code>&lt;?php KSPLUD_Display::display_published_date(123); ?&gt;</code></p>
                            <p><code>&lt;?php echo KSPLUD_Display::get_published_date(null, 'Y/m/d'); ?&gt;</code></p>
                        </div>

                        <div class="ksplud-info-box">
                            <h3><?php _e('プラグイン情報', 'kashiwazaki-seo-published-last-updated-dates'); ?></h3>
                            <p><?php _e('バージョン:', 'kashiwazaki-seo-published-last-updated-dates'); ?> <?php echo KSPLUD_VERSION; ?></p>
                            <p><?php _e('作者:', 'kashiwazaki-seo-published-last-updated-dates'); ?> 柏崎 剛</p>
                            <p><a href="https://tsuyoshikashiwazaki.jp" target="_blank"><?php _e('SEO対策研究室', 'kashiwazaki-seo-published-last-updated-dates'); ?></a></p>
                        </div>
                    </div>
                </div>

                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    public function sanitize_settings($input) {
        $sanitized = array();

        $sanitized['display_position'] = isset($input['display_position']) ? sanitize_text_field($input['display_position']) : 'both';
        $sanitized['post_types'] = isset($input['post_types']) && is_array($input['post_types']) ? array_map('sanitize_text_field', $input['post_types']) : array('post');
        $sanitized['display_style'] = isset($input['display_style']) ? sanitize_text_field($input['display_style']) : 'icon_text';
        $sanitized['date_format'] = isset($input['date_format']) ? sanitize_text_field($input['date_format']) : 'Y年n月j日';
        $sanitized['show_time'] = isset($input['show_time']) && $input['show_time'] == '1';
        $sanitized['enable_schema'] = isset($input['enable_schema']) && $input['enable_schema'] == '1';
        // 投稿タイプ別設定のサニタイズ
        $sanitized['post_type_settings'] = array();
        if (isset($input['post_type_settings']) && is_array($input['post_type_settings'])) {
            foreach ($input['post_type_settings'] as $post_type => $settings) {
                if (is_array($settings)) {
                    $sanitized['post_type_settings'][sanitize_text_field($post_type)] = array(
                        'show_published' => isset($settings['show_published']) && $settings['show_published'] == '1',
                        'show_updated' => isset($settings['show_updated']) && $settings['show_updated'] == '1'
                    );
                }
            }
        }

        // 後方互換性のためのレガシー設定も保持
        $sanitized['show_published'] = isset($input['show_published']) && $input['show_published'] == '1';
        $sanitized['show_updated'] = isset($input['show_updated']) && $input['show_updated'] == '1';
        $sanitized['published_text'] = isset($input['published_text']) ? sanitize_text_field($input['published_text']) : '公開日';
        $sanitized['updated_text'] = isset($input['updated_text']) ? sanitize_text_field($input['updated_text']) : '更新日';
        $sanitized['date_color'] = isset($input['date_color']) ? sanitize_hex_color($input['date_color']) : '#0ea5e9';
        $sanitized['design_pattern'] = isset($input['design_pattern']) && in_array($input['design_pattern'], array('badge', 'simple', 'box', 'line')) ? sanitize_text_field($input['design_pattern']) : 'badge';
        $sanitized['hide_if_not_modified'] = isset($input['hide_if_not_modified']) && $input['hide_if_not_modified'] == '1';
        $sanitized['modified_threshold'] = 86400;
        $sanitized['icon_style'] = 'default';
        $sanitized['custom_css'] = isset($input['custom_css']) ? wp_strip_all_tags($input['custom_css']) : '';

        return $sanitized;
    }
}
