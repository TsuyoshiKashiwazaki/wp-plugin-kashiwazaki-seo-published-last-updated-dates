<?php
if (!defined('ABSPATH')) {
    exit;
}

class KSPLUD_Schema {
    
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
        if ($this->settings->should_display_schema()) {
            add_action('wp_head', array($this, 'output_schema_markup'));
        }
    }
    
    public function output_schema_markup() {
        if (!is_singular()) {
            return;
        }
        
        $post_type = get_post_type();
        if (!$this->settings->is_enabled_for_post_type($post_type)) {
            return;
        }
        
        $post_id = get_the_ID();
        $schema = $this->generate_article_schema($post_id);
        
        if (!empty($schema)) {
            echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
        }
    }
    
    private function generate_article_schema($post_id) {
        $post = get_post($post_id);
        if (!$post) {
            return array();
        }
        
        $url = get_permalink($post_id);
        $date_published = get_the_date('c', $post_id);
        $date_modified = get_the_modified_date('c', $post_id);
        
        $graph = array();
        
        $graph[] = array(
            '@type' => 'DigitalDocument',
            '@id' => $url . '#doc',
            'url' => $url,
            'datePublished' => $date_published,
            'dateModified' => $date_modified
        );
        
        $graph[] = array(
            '@type' => 'CreateAction',
            '@id' => $url . '#create',
            'startTime' => $date_published,
            'object' => array(
                '@id' => $url . '#doc'
            )
        );
        
        if ($date_modified !== $date_published) {
            $graph[] = array(
                '@type' => 'UpdateAction',
                '@id' => $url . '#update',
                'startTime' => $date_modified,
                'object' => array(
                    '@id' => $url . '#doc'
                )
            );
        }
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@graph' => $graph
        );
        
        return $schema;
    }
}