<?php
/**
 * Plugin Name: Elementor Enhanced Related Products
 * Description: Advanced widget for complete control over related products logic (Category, Tag, Both, Combined, Manual).
 * Version: 1.2.0
 * Author: Your Name
 * Text Domain: elementor-enhanced-related
 * Requires PHP: 7.4
 * Requires at least: 6.0
 * Elementor requires at least: 3.5
 * Elementor tested up to: 3.34
 * WC requires at least: 6.0
 * WC tested up to: 10.4
 */

if (!defined('ABSPATH')) {
    exit;
}

final class Elementor_Enhanced_Related_Products_Plugin {
    const VERSION = '1.2.0';
    const MINIMUM_ELEMENTOR_VERSION = '3.5.0';
    const MINIMUM_PHP_VERSION = '7.4';

    private static $instance = null;

    public static function instance(): ?Elementor_Enhanced_Related_Products_Plugin {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
    }

    public function init(): void {
        // Check if Elementor is loaded
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_elementor']);
            return;
        }

        // Check Elementor version
        if (!version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
            return;
        }

        // Check PHP version
        if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_php_version']);
            return;
        }

        // Register widget
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
        
        // Load translations
        load_plugin_textdomain('elementor-enhanced-related', false, dirname(plugin_basename(__FILE__)) . '/languages/');
        
        // Load frontend styles conditionally
        add_action('wp_enqueue_scripts', [$this, 'conditionally_enqueue_styles']);
    }

    public function register_widgets($widgets_manager): void {
        require_once __DIR__ . '/widget-enhanced-related-products.php';
        $widgets_manager->register(new \Elementor_Enhanced_Related_Products_Widget());
    }

    public function conditionally_enqueue_styles(): void {
        // Only enqueue if widget is present on page
        if (defined('ELEMENTOR_VERSION') && class_exists('Elementor\Plugin')) {
            $document = \Elementor\Plugin::$instance->documents->get(get_the_ID());
            if ($document && $document->is_built_with_elementor()) {
                wp_enqueue_style(
                    'eerp-frontend', 
                    plugin_dir_url(__FILE__) . 'assets/css/frontend.css', 
                    [], 
                    self::VERSION
                );
            }
        }
    }

    public function admin_notice_missing_elementor(): void {
        if (isset($_GET['activate'])) unset($_GET['activate']);
        $message = sprintf(
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'elementor-enhanced-related'),
            '<strong>' . esc_html__('Elementor Enhanced Related Products', 'elementor-enhanced-related') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'elementor-enhanced-related') . '</strong>'
        );
        printf('<div class="notice notice-warning is-dismissible"><p>%s</p></div>', $message);
    }

    public function admin_notice_minimum_elementor_version(): void {
        $message = sprintf(
            esc_html__('"%1$s" requires "%2$s" version %3$s or higher.', 'elementor-enhanced-related'),
            '<strong>' . esc_html__('Elementor Enhanced Related Products', 'elementor-enhanced-related') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'elementor-enhanced-related') . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );
        printf('<div class="notice notice-warning is-dismissible"><p>%s</p></div>', $message);
    }

    public function admin_notice_minimum_php_version(): void {
        $message = sprintf(
            esc_html__('"%1$s" requires "%2$s" version %3$s or higher.', 'elementor-enhanced-related'),
            '<strong>' . esc_html__('Elementor Enhanced Related Products', 'elementor-enhanced-related') . '</strong>',
            '<strong>' . esc_html__('PHP', 'elementor-enhanced-related') . '</strong>',
            self::MINIMUM_PHP_VERSION
        );
        printf('<div class="notice notice-warning is-dismissible"><p>%s</p></div>', $message);
    }
}

// Initialize plugin
Elementor_Enhanced_Related_Products_Plugin::instance();
