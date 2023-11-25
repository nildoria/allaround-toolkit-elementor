<?php
namespace GlobalAssistant;

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Carousel extends Widget_Base
{

    public function get_name()
    {
        return 'ala-carousel';
    }

    public function get_title()
    {
        return __('Review Carousel Slider', 'allaround-addons');
    }

    public function get_categories()
    {
        return array('allaroundwidget');
    }

    public function get_icon()
    {
        return 'eicon-allaround-icon';
    }


    public function get_style_depends() {
        return array( 'my-custom-widget-style' );
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
            'count',
            [
                'label' => __('Number of review display', 'themepaw-companion'),
                'type' => Controls_Manager::NUMBER,
				'default' => '5'
            ]
        );

		$this->add_control(
            'show_navigation',
            [
                'label' => __('Show Navigation', 'allaround-addons'),
                'type' => Controls_Manager::SWITCHER,
            	'label_on' => esc_html__( 'Yes', 'allaround-addons' ),
            	'label_off' => esc_html__( 'No', 'allaround-addons' ),
            	'return_value' => 'yes',
            	'default' => 'no',
            ]
        );

		$this->add_control(
            'show_pagination',
            [
                'label' => __('Show Pagination', 'allaround-addons'),
                'type' => Controls_Manager::SWITCHER,
            	'label_on' => esc_html__( 'Yes', 'allaround-addons' ),
            	'label_off' => esc_html__( 'No', 'allaround-addons' ),
            	'return_value' => 'yes',
            	'default' => 'yes',
            ]
        );


		$this->end_controls_section();

	}

	protected function render() {
		$instance = $this->get_settings_for_display();
		$count = ! empty( $instance['count'] ) ? absint( $instance['count'] ) : 5;
		$navigation = $instance['show_navigation'];
		$pagination = $instance['show_pagination'];

        $review_args = array(
            'posts_per_page' => $count,
            'post_type'      => 'review',
            'post_status'    => 'publish',
            'order'          => 'DESC'
        );
        $review_qry  = new \WP_Query( $review_args );
        ?>
		<div class="al-carousel-container swiper-container">
		<div class="swiper-wrapper">
			<?php if ( $review_qry->have_posts() ) : ?>
			<?php
			while ( $review_qry->have_posts() ) : $review_qry->the_post(); 
			
			// $rating = get_post_meta( get_the_ID(), 'rating', true );
			$name = get_post_meta( get_the_ID(), 'name', true );
			// $custom_date = get_post_meta( get_the_ID(), 'custom_date', true );
			// $email = get_post_meta( get_the_ID(), 'email', true );
			// $avatar = get_post_meta( get_the_ID(), 'avatar', true );
			$thumb = get_post_meta( get_the_ID(), 'review_thumb', true );
			$thumb_url = !empty($thumb) ? wp_get_attachment_image_src($thumb, 'large', true) : '';


			// $user_email = ! empty( $email ) ? $email : null;
			// $gravatar = ! empty( $avatar ) ? '<img src="'.wp_get_attachment_url( (int) $avatar ).'"/>' : get_avatar( $user_email, 100, 'mystery' );
			$review_thumb = ! empty( $thumb_url ) ? '<a href="'.wp_get_attachment_url( (int) $thumb ).'"><img src="'. esc_url($thumb_url[0]) .'"/></a>' : '<img src="'.get_template_directory_uri() . '/assets/images/icon-placeholder.png'.'"/>';
			$user_name = ! empty( $name ) ? $name : esc_html__('Anonymous', 'hello-elementor');


			// $the_date = ! empty( $custom_date ) ? date_i18n('j F ,Y', strtotime($custom_date)) : get_the_date( 'j F ,Y' );

			?>
			<div class="carousel-item swiper-slide">
				<div class="review-carousel-thumb">
                    <?php echo $review_thumb; ?>
				</div>
				<div class="review-carousel-title">
					<?php echo $user_name; ?>
				</div>
				<div class="review-carousel-description">
					<?php the_content(); ?>
				</div>
				<div class="review-carousel-author">
					<?php the_title(); ?>
				</div>
			
			</div>
			
			<?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
			<?php else : ?>
                <p><?php esc_html_e( 'Sorry, no review found.', 'hello-elmentor' ); ?></p>
            <?php endif; ?>
		</div>
		<?php if( !empty( $pagination ) ) : ?>
		<div class="swiper-pagination"></div>
		<?php endif; ?>
		<?php if( !empty( $navigation ) ) : ?>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
		<?php endif; ?>
		</div>
		<script>
            jQuery(document).ready(function() {
            // Swiper: Slider
                var swiperReview = new Swiper('.al-carousel-container', {
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
					centeredSlides: true,
					roundLengths: true,
                    paginationClickable: true,
                    spaceBetween: 1,
                    // autoplay: 
                    // {
                    //   delay: 2000,
                    // },
		            direction: 'horizontal',
                    breakpoints: {
                        1920: {
                            slidesPerView: 3,
                            spaceBetween: 1
                        },
                        1028: {
                            slidesPerView: 3,
                            spaceBetween: 1
                        },
                        767: {
                            slidesPerView: 3,
                            spaceBetween: 1
                        },
                        150: {
                            slidesPerView: 1,
                            spaceBetween: 1
                        }
                    }
                });
            });
		</script>
		<?php
	}


	

	protected function __content_template() {}

	public function render_plain_content( $instance = [] ) {}



}