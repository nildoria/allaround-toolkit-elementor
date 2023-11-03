<?php
namespace GlobalAssistant;

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Product_category extends Widget_Base {

	public function get_name() {
		return 'ala-product-cat';
	}

	public function get_title() {
		return __( 'Product Category', 'allaround-addons' );
	}

    public function get_categories()
    {
        return array( 'allaroundwidget' );
    }

    public function get_icon()
    {
        return 'eicon-allaround-icon';
    }

	protected function _register_controls() {

		$this->start_controls_section(
			'general',
			[
				'label' => esc_html__( 'ALA Product Catagory', 'globalasst' ),
			]
		);

        $this->add_control(
            'all_categories',
            [
                'label' => __('Display all categories', 'themepaw-companion'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

		$this->add_control(
            'product_cat',
            [
                'label' => __('Select a category', 'themepaw-companion'),
                'type' => Controls_Manager::SELECT2,
                'options' => $this->taxomony_list('product_cat'),
                'multiple' => false,
                'condition'   => [
					'all_categories' => '',
				]
            ]
        );

        $this->add_control(
			'title',
			[
				'label' => __( 'Title', 'themepaw-companion' ),
				'type' => Controls_Manager::TEXT,
				'default' => ''
			]
		);

        $this->add_control(
            'hide_items',
            [
                'label' => __('Hide Item List', 'themepaw-companion'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );
		
		
		$this->end_controls_section();

	}

	function taxomony_list($taxonomy = 'category', $top_lavel = true) 
    {

        $taxonomy_exist = taxonomy_exists($taxonomy);
        if (!$taxonomy_exist) {
            return;
        }
        $term_args = array( 
            'taxonomy' => $taxonomy,
            'hide_empty' => 1
        );

        if( true === $top_lavel ) {
            $term_args['parent'] = 0;
        }

        $terms = get_terms( $term_args );

        $get_terms = array();

        if ( !empty($terms) ) {
            foreach( $terms as $term ) :
                $get_terms[$term->term_id] = $term->name;
            endforeach;
        }
        
        return $get_terms;
    }
	
    function taxomony_ids($taxonomy = 'category', $top_lavel = true) 
    {

        $taxonomy_exist = taxonomy_exists($taxonomy);
        if (!$taxonomy_exist) {
            return;
        }
        $term_args = array( 
            'taxonomy' => $taxonomy,
            'hide_empty' => 1
        );

        if( true === $top_lavel ) {
            $term_args['parent'] = 0;
        }

        $terms = get_terms( $term_args );

        $get_terms = array();

        if ( !empty($terms) ) {
            foreach( $terms as $term ) :
                $get_terms[] = $term->term_id;
            endforeach;
        }
        
        return $get_terms;
    }

	protected function render() {

		$instance = $this->get_settings_for_display();

		$term_id = ! empty( $instance['product_cat'] ) ? sanitize_text_field( $instance['product_cat'] ) : '';
		$title = ! empty( $instance['title'] ) ? sanitize_text_field( $instance['title'] ) : '';
		$all_categories = ! empty( $instance['all_categories'] ) ? sanitize_text_field( $instance['all_categories'] ) : '';
		$hide_items = ! empty( $instance['hide_items'] ) ? sanitize_text_field( $instance['hide_items'] ) : '';

        $taxonomy = 'product_cat';
        if( ! empty( $all_categories ) ) {
            $termchildren = $this->taxomony_ids('product_cat', false);
            $main_title = ! empty( $title ) ? $title : esc_html__( 'All product Categories', 'hello-elmentor' );
        } elseif( ! empty( $term_id ) ) {
            $termchildren = get_term_children( $term_id, $taxonomy );
            $mainterm = get_term( $term_id, $taxonomy );
            $main_title = ! empty( $title ) ? $title : $mainterm->name;
        }

        if( ! empty( $termchildren ) ) :
		?>
        <div class="alarnd--category-page-wrap">
            <div class="allaround--full-bg">
                <div class="allaround--review-info">
                    <h2><?php echo esc_html( $main_title ); ?></h2>
                    <div class="allaround--review-counter">
                        <a href="#customer-reviews">
                        <?php echo alarnd_total_review_icons(); ?>
                        <p><?php echo alarnd_all_review_count(); ?> <?php esc_html_e('Reviews', 'hello-elementor'); ?></p>
                        </a>
                    </div>
                </div>
            </div>
            <?php if(empty( $hide_items )) : ?>
            <div class="allaround--products-cats">
                <?php 
                if ( !empty( $termchildren ) && !is_wp_error( $termchildren ) ) :
                foreach( $termchildren as $children ) :
                    $chilterm = get_term( $children, $taxonomy );
                    if( $chilterm->count === 0 ) {
                        continue;
                    }
                    $title = $chilterm->name;
                    $term_link = get_term_link( $chilterm );
                    $get_product_count = alarnd_get_product_by_term($children);
                    if( $chilterm->count === 1 && ! empty( $get_product_count ) ) {
                        $term_link = get_permalink( $get_product_count );
                        // $title = get_the_title( $get_product_count );
                    }
                    $thumb_id = get_term_meta( $children, 'thumbnail_id', true );
                    $thumb_url = ! empty( $thumb_id ) ? wp_get_attachment_image_url( $thumb_id, 'full' ) : get_template_directory_uri() . '/assets/images/icon-placeholder.png';
                    ?>
                    <a href="<?php echo esc_url( $term_link ); ?>" class="allaround--single-cat-item">
                        <img src="<?php echo esc_url( $thumb_url ); ?>" alt="">
                        <h5><?php echo esc_html( $title ); ?></h5>
                    </a>
                <?php endforeach;
                else :
                    $product_args = array(
                        'numberposts' => 50000,
                        'post_type'      => 'product',
                        'post_status'    => 'publish',
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'product_cat',
                                'field' => 'term_id',
                                'terms' => $term_id
                            )
                        )
                    );
                    $all_products  = get_posts( $product_args );
                    foreach( $all_products as $product ) : 
                        $icon_id = get_field( 'product_icon', $product->ID );
                        $thumb_url = ! empty( $icon_id ) ? wp_get_attachment_image_url( $icon_id, 'full' ) : get_template_directory_uri() . '/assets/images/icon-placeholder.png';
                    ?>
                    <a href="<?php echo esc_url( get_permalink( $product->ID ) ); ?>" class="allaround--single-cat-item">
                        <img src="<?php echo esc_url( $thumb_url ); ?>" alt="">
                        <h5><?php echo esc_html( get_the_title( $product->ID ) ); ?></h5>
                    </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
		<?php
        endif;
	}

	protected function __content_template() {}

	public function render_plain_content( $instance = [] ) {}

}