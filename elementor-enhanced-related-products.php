<?php
/**
 * Plugin Name: Elementor Enhanced Related Products
 * Description: Widget avanzado para control total sobre productos relacionados (Categoría, Etiqueta, Ambos, Combinado).
 * Version: 1.1.0
 * Author: Tu Nombre
 * Text Domain: elementor-enhanced-related
 * Requires PHP: 7.4
 * Requires at least: 6.0
 * Elementor requires at least: 3.5
 * Elementor tested up to: 3.34
 */

if (!defined('ABSPATH')) {
    exit;
}

final class Elementor_Enhanced_Related_Products_Plugin {
    const VERSION = '1.1.0';
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
        // Verificar Elementor
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_elementor']);
            return;
        }

        // Verificar versión de Elementor
        if (!version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
            return;
        }

        // Verificar PHP
        if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_php_version']);
            return;
        }

        // Registrar widget
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
        
        // Cargar traducciones
        load_plugin_textdomain('elementor-enhanced-related', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    public function register_widgets($widgets_manager): void {
        require_once __DIR__ . '/widget-enhanced-related-products.php';
        $widgets_manager->register(new \Elementor_Enhanced_Related_Products_Widget());
    }

    public function admin_notice_missing_elementor(): void {
        if (isset($_GET['activate'])) unset($_GET['activate']);
        $message = sprintf(
            esc_html__('"%1$s" requiere "%2$s" para funcionar.', 'elementor-enhanced-related'),
            '<strong>' . esc_html__('Elementor Enhanced Related Products', 'elementor-enhanced-related') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'elementor-enhanced-related') . '</strong>'
        );
        printf('<div class="notice notice-warning is-dismissible"><p>%s</p></div>', $message);
    }

    public function admin_notice_minimum_elementor_version(): void {
        $message = sprintf(
            esc_html__('"%1$s" requiere "%2$s" versión %3$s o superior.', 'elementor-enhanced-related'),
            '<strong>' . esc_html__('Elementor Enhanced Related Products', 'elementor-enhanced-related') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'elementor-enhanced-related') . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );
        printf('<div class="notice notice-warning is-dismissible"><p>%s</p></div>', $message);
    }

    public function admin_notice_minimum_php_version(): void {
        $message = sprintf(
            esc_html__('"%1$s" requiere "%2$s" versión %3$s o superior.', 'elementor-enhanced-related'),
            '<strong>' . esc_html__('Elementor Enhanced Related Products', 'elementor-enhanced-related') . '</strong>',
            '<strong>' . esc_html__('PHP', 'elementor-enhanced-related') . '</strong>',
            self::MINIMUM_PHP_VERSION
        );
        printf('<div class="notice notice-warning is-dismissible"><p>%s</p></div>', $message);
    }
}

// Iniciar plugin
Elementor_Enhanced_Related_Products_Plugin::instance();
