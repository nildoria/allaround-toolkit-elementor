<?php
namespace GlobalAssistant;

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Carousel_List extends Widget_Base
{

    public function get_name()
    {
        return 'ala-carousel';
    }

    public function get_title()
    {
        return __('Carousel Slider List', 'allaround-addons');
    }

    public function get_categories()
    {
        return array('basic');
    }

    public function get_icon()
    {
        return 'eicon-carousel';
    }

	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'textdomain' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'list',
			[
				'label' => esc_html__( 'List', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => [
					[
						'name' => 'image',
						'label' => esc_html__( 'Choose Image', 'textdomain' ),
						'type' => \Elementor\Controls_Manager::MEDIA,
						'default' => [
        					'url' => \Elementor\Utils::get_placeholder_image_src(),
        				],
					],
					[
						'name' => 'text',
						'label' => esc_html__( 'Text', 'textdomain' ),
						'type' => \Elementor\Controls_Manager::TEXT,
						'placeholder' => esc_html__( 'List Item', 'textdomain' ),
						'default' => esc_html__( 'List Item', 'textdomain' ),
					],
					[
						'name' => 'link',
						'label' => esc_html__( 'Link', 'textdomain' ),
						'type' => \Elementor\Controls_Manager::URL,
						'placeholder' => esc_html__( 'https://your-link.com', 'textdomain' ),
					],
				],
				'default' => [
					[
						'text' => esc_html__( 'List Item #1', 'textdomain' ),
						'link' => 'https://elementor.com/',
					],
					[
						'text' => esc_html__( 'List Item #2', 'textdomain' ),
						'link' => 'https://elementor.com/',
					],
				],
				'title_field' => '{{{ text }}}',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="al-carousel-container swiper-container">
		<div class="swiper-wrapper">
		<?php foreach ( $settings['list'] as $index => $item ) : ?>
			<div class="carousel-item swiper-slide">
				<?php
				if ( ! $item['link']['url'] ) {
				    // Get image by id
	                echo wp_get_attachment_image( $item['image']['id'], 'medium' );
					echo $item['text'];
				} else {
					?><a href="<?php echo esc_url( $item['link']['url'] ); ?>"><?php echo wp_get_attachment_image( $item['image']['id'], 'medium' );?><span class="carousel-item_text"><?php echo $item['text']; ?></span></a><?php
				}
				?>
			</div>
		<?php endforeach; ?>
		</div>
		<div class="swiper-pagination"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
		</div>
		<script>
            jQuery(document).ready(function() {
            // Swiper: Slider
                new Swiper('.al-carousel-container', {
                    loop: true,
        			pagination: {
        				el: '.swiper-pagination',
        				clickable: true,
        			},
        			navigation: {
        				nextEl: '.swiper-button-next',
        				prevEl: '.swiper-button-prev',
        			},
                    slidesPerView: 3,
                    paginationClickable: true,
                    spaceBetween: 15,
                    breakpoints: {
                        1920: {
                            slidesPerView: 4,
                            spaceBetween: 30
                        },
                        1028: {
                            slidesPerView: 3,
                            spaceBetween: 30
                        },
                        480: {
                            slidesPerView: 1,
                            spaceBetween: 10
                        }
                    }
                });
            });
		</script>
		<?php
	}



}