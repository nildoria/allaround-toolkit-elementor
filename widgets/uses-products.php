<?php
namespace GlobalAssistant;

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Uses_Products extends Widget_Base
{

    public function get_name()
    {
        return 'ala-selected-products';
    }

    public function get_title()
    {
        return __('Selected Products', 'allaround-addons');
    }

    public function get_categories()
    {
        return array('basic');
    }

    public function get_icon()
    {
        return 'eicon-allaround-icon';
    }
    

protected function register_controls() {
    $options = array();

    // Query WooCommerce products
    $product_args = array(
        'post_type' => 'product',
        'posts_per_page' => -1, // Retrieve all products
    );

    $products = get_posts($product_args);

    if ($products) {
        foreach ($products as $product) {
            $options[$product->ID] = get_the_title($product->ID);
        }
    }

    $this->start_controls_section(
        'section_content',
        [
            'label' => esc_html__('Content', 'allaround-addons'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]
    );

    $this->add_control(
        'selected_products',
        [
            'label' => __('Select Products', 'allaround-addons'),
            'type' => \Elementor\Controls_Manager::SELECT2,
            'label_block' => true,
            'multiple' => true,
            'options' => $options,
        ]
    );

    $this->end_controls_section();
}


    protected function render() {
        $settings = $this->get_settings_for_display();
        $selected_products = $settings['selected_products'];

        if (!empty($selected_products)) {
            echo '<div class="allaround--products-cats">';
            foreach ($selected_products as $product_id) {
                $product = wc_get_product($product_id);
                
                $icon_id = get_post_meta($product_id, 'product_icon', true );
                $thumb_url = ! empty( $icon_id ) ? wp_get_attachment_image_url( $icon_id, 'full' ) : get_the_post_thumbnail_url( $product_id, 'full' );
                $product_short_name = get_post_meta($product_id, 'product_short_name', true);
                $thumbnail_class = !empty($icon_id) ? 'uses-product-icon' : 'uses-product-thumb';

                echo '<a href="'. esc_url( get_permalink( $product_id ) ) .'" class="allaround--single-cat-item">';

                if ($thumb_url) {
                    echo '<img src="' . esc_url($thumb_url) . '" alt="Product Icon" class="' . esc_attr($thumbnail_class) . '" />';
                }
                
                if ($product_short_name) {
                    echo '<h5>' . esc_html($product_short_name) . '</h5>';
                } else {
                    echo '<h5>' . $product->get_title() . '</h5>';
                }
                echo '</a>';
            }
            echo '</div>';
        }
    }


}