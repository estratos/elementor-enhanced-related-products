<?php
if (!defined('ABSPATH')) {
    exit;
}

class Elementor_Enhanced_Related_Products_Widget extends \Elementor\Widget_Base {
    
    public function get_name(): string {
        return 'enhanced_related_products';
    }

    public function get_title(): string {
        return esc_html__('Enhanced Related Products', 'elementor-enhanced-related');
    }

    public function get_icon(): string {
        return 'eicon-product-related';
    }

    public function get_categories(): array {
        return ['woocommerce-elements'];
    }

    public function get_keywords(): array {
        return ['related', 'products', 'woocommerce', 'category', 'tag'];
    }

    protected function register_controls(): void {
        // SECCIÓN CONTENIDO
        $this->start_controls_section('section_content', [
            'label' => esc_html__('Product Query', 'elementor-enhanced-related'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('filter_logic', [
            'label' => esc_html__('Filter Logic', 'elementor-enhanced-related'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'both',
            'options' => [
                'category' => esc_html__('Category Only', 'elementor-enhanced-related'),
                'tag'      => esc_html__('Tag Only', 'elementor-enhanced-related'),
                'both'     => esc_html__('Both (AND - WooCommerce Default)', 'elementor-enhanced-related'),
                'combined' => esc_html__('Combined (OR - Category OR Tag)', 'elementor-enhanced-related'),
                'manual'   => esc_html__('Manual Selection', 'elementor-enhanced-related'),
            ],
            'description' => esc_html__('"Both (AND)": Products sharing at least ONE category AND ONE tag. "Combined (OR)": Products sharing at least ONE category OR ONE tag.', 'elementor-enhanced-related'),
        ]);

        $this->add_control('manual_products', [
            'label' => esc_html__('Select Products', 'elementor-enhanced-related'),
            'type' => \Elementor\Controls_Manager::SELECT2,
            'label_block' => true,
            'multiple' => true,
            'options' => $this->get_all_products(),
            'default' => [],
            'condition' => ['filter_logic' => 'manual'],
            'description' => esc_html__('Start typing to search for products. Overrides automatic filtering.', 'elementor-enhanced-related'),
        ]);

        $this->add_control('posts_per_page', [
            'label' => esc_html__('Products Count', 'elementor-enhanced-related'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 4,
            'min' => 1,
            'max' => 50,
        ]);

        $this->add_control('columns', [
            'label' => esc_html__('Columns', 'elementor-enhanced-related'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => '4',
            'options' => [
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
            ],
        ]);

        $this->add_control('orderby', [
            'label' => esc_html__('Order By', 'elementor-enhanced-related'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'rand',
            'options' => [
                'rand'     => esc_html__('Random', 'elementor-enhanced-related'),
                'date'     => esc_html__('Date', 'elementor-enhanced-related'),
                'title'    => esc_html__('Title', 'elementor-enhanced-related'),
                'price'    => esc_html__('Price', 'elementor-enhanced-related'),
                'popularity' => esc_html__('Popularity', 'elementor-enhanced-related'),
                'rating'   => esc_html__('Rating', 'elementor-enhanced-related'),
                'menu_order' => esc_html__('Menu Order', 'elementor-enhanced-related'),
            ],
        ]);

        $this->add_control('order', [
            'label' => esc_html__('Order', 'elementor-enhanced-related'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'DESC',
            'options' => [
                'ASC'  => esc_html__('Ascending', 'elementor-enhanced-related'),
                'DESC' => esc_html__('Descending', 'elementor-enhanced-related'),
            ],
        ]);

        $this->add_control('show_heading', [
            'label' => esc_html__('Show Heading', 'elementor-enhanced-related'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'label_on' => esc_html__('Yes', 'elementor-enhanced-related'),
            'label_off' => esc_html__('No', 'elementor-enhanced-related'),
        ]);

        $this->add_control('heading_text', [
            'label' => esc_html__('Heading Text', 'elementor-enhanced-related'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => esc_html__('Related Products', 'elementor-enhanced-related'),
            'condition' => ['show_heading' => 'yes'],
            'label_block' => true,
        ]);

        $this->end_controls_section();

        // SECCIÓN DE ESTILO
        $this->start_controls_section('section_style', [
            'label' => esc_html__('Products Grid', 'elementor-enhanced-related'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('columns_gap', [
            'label' => esc_html__('Columns Gap', 'elementor-enhanced-related'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 100]],
            'selectors' => [
                '{{WRAPPER}} .eerp-products-grid' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('rows_gap', [
            'label' => esc_html__('Rows Gap', 'elementor-enhanced-related'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 100]],
            'selectors' => [
                '{{WRAPPER}} .eerp-products-grid' => 'row-gap: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();
    }

    private function get_all_products(): array {
        $products = get_posts([
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        ]);
        
        $options = [];
        foreach ($products as $product) {
            $options[$product->ID] = $product->post_title . ' (ID: ' . $product->ID . ')';
        }
        return $options;
    }

    protected function render(): void {
        global $product;
        
        if (!is_a($product, 'WC_Product')) {
            if (current_user_can('manage_options')) {
                echo '<p class="eerp-admin-notice">' . esc_html__('Enhanced Related Products widget works only on single product pages.', 'elementor-enhanced-related') . '</p>';
            }
            return;
        }

        $settings = $this->get_settings_for_display();
        $filter_logic = $settings['filter_logic'] ?? 'both';
        
        // LÓGICA DE CONSULTA MEJORADA
        if ($filter_logic === 'manual' && !empty($settings['manual_products'])) {
            // SELECCIÓN MANUAL
            $product_ids = array_map('intval', $settings['manual_products']);
            $product_ids = array_diff($product_ids, [$product->get_id()]);
            
            if (empty($product_ids)) {
                echo '<p>' . esc_html__('No related products selected.', 'elementor-enhanced-related') . '</p>';
                return;
            }
            
            $args = [
                'post_type' => 'product',
                'post_status' => 'publish',
                'posts_per_page' => $settings['posts_per_page'] ?? 4,
                'post__in' => $product_ids,
                'orderby' => 'post__in',
                'ignore_sticky_posts' => 1,
            ];
        } else {
            // LÓGICA AUTOMÁTICA
            $product_id = $product->get_id();
            $category_ids = wp_get_post_terms($product_id, 'product_cat', ['fields' => 'ids']);
            $tag_ids = wp_get_post_terms($product_id, 'product_tag', ['fields' => 'ids']);
            
            $args = [
                'post_type' => 'product',
                'post_status' => 'publish',
                'posts_per_page' => $settings['posts_per_page'] ?? 4,
                'post__not_in' => [$product_id],
                'orderby' => $settings['orderby'] ?? 'rand',
                'order' => $settings['order'] ?? 'DESC',
            ];

            // CONSTRUIR TAX_QUERY SEGÚN LÓGICA
            switch ($filter_logic) {
                case 'category':
                    if (!empty($category_ids)) {
                        $args['tax_query'] = [[
                            'taxonomy' => 'product_cat',
                            'field' => 'term_id',
                            'terms' => $category_ids,
                        ]];
                    }
                    break;
                    
                case 'tag':
                    if (!empty($tag_ids)) {
                        $args['tax_query'] = [[
                            'taxonomy' => 'product_tag',
                            'field' => 'term_id',
                            'terms' => $tag_ids,
                        ]];
                    }
                    break;
                    
                case 'both':
                    if (!empty($category_ids) && !empty($tag_ids)) {
                        $args['tax_query'] = [
                            'relation' => 'AND',
                            [
                                'taxonomy' => 'product_cat',
                                'field' => 'term_id',
                                'terms' => $category_ids,
                            ],
                            [
                                'taxonomy' => 'product_tag',
                                'field' => 'term_id',
                                'terms' => $tag_ids,
                            ]
                        ];
                    }
                    break;
                    
                case 'combined':
                    if (!empty($category_ids) || !empty($tag_ids)) {
                        $args['tax_query'] = [
                            'relation' => 'OR'
                        ];
                        if (!empty($category_ids)) {
                            $args['tax_query'][] = [
                                'taxonomy' => 'product_cat',
                                'field' => 'term_id',
                                'terms' => $category_ids,
                            ];
                        }
                        if (!empty($tag_ids)) {
                            $args['tax_query'][] = [
                                'taxonomy' => 'product_tag',
                                'field' => 'term_id',
                                'terms' => $tag_ids,
                            ];
                        }
                    }
                    break;
            }
        }

        // EJECUTAR CONSULTA
        $related_products = new WP_Query($args);
        
        if (!$related_products->have_posts()) {
            if (current_user_can('manage_options')) {
                echo '<p>' . esc_html__('No related products found with current criteria.', 'elementor-enhanced-related') . '</p>';
            }
            return;
        }

        // GUARDAR CONFIGURACIÓN DE BUCLE WOOCOMMERCE
        $original_columns = wc_get_loop_prop('columns', 4);
        wc_set_loop_prop('columns', $settings['columns'] ?? 4);
        
        // RENDERIZAR
        echo '<div class="eerp-enhanced-related-products">';
        
        if ($settings['show_heading'] === 'yes' && !empty($settings['heading_text'])) {
            echo '<h2 class="eerp-heading">' . wp_kses_post($settings['heading_text']) . '</h2>';
        }
        
        echo '<div class="eerp-products-grid" style="display: grid; grid-template-columns: repeat(' . 
             esc_attr($settings['columns'] ?? 4) . ', 1fr);">';
        
        woocommerce_product_loop_start();
        
        while ($related_products->have_posts()) {
            $related_products->the_post();
            wc_get_template_part('content', 'product');
        }
        
        woocommerce_product_loop_end();
        
        echo '</div></div>';
        
        // RESTAURAR CONFIGURACIÓN
        wp_reset_postdata();
        wc_set_loop_prop('columns', $original_columns);
    }
}
