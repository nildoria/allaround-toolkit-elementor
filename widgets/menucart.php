<?php
namespace GlobalAssistant;

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Menu_Cart extends Widget_Base {

	public function get_name() {
		return 'ala-menu-cart';
	}

	public function get_title() {
		return __( 'Menu Cart', 'allaround-addons' );
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

		// $this->start_controls_section(
		// 	'general',
		// 	[
		// 		'label' => esc_html__( 'Count Color', 'globalasst' ),
		// 	]
		// );

		// $this->add_control(
        //     'product_cat',
        //     [
        //         'label' => __('Select a category', 'themepaw-companion'),
        //         'type' => Controls_Manager::SELECT2,
        //         'options' => $this->taxomony_list('product_cat'),
        //         'multiple' => false
        //     ]
        // );
		
		
		// $this->end_controls_section();

	}

	protected function render() {

		$instance = $this->get_settings_for_display();

		$term_id = ! empty( $instance['product_cat'] ) ? sanitize_text_field( $instance['product_cat'] ) : '';

        $cart_count = WC()->cart->cart_contents_count; // Set variable for cart item count
        $cart_url = wc_get_cart_url();
		?>
        <a class="alarnd__cart_menu_item" href="<?php echo esc_url( $cart_url ); ?>"><span class="alarnd__cart_icon" data-counter="<?php echo esc_attr( $cart_count ); ?>"></span></a>
		<?php
	}

	protected function __content_template() {}

	public function render_plain_content( $instance = [] ) {}

}

