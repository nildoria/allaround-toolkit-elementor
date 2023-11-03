<?php
namespace GlobalAssistant;

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Review extends Widget_Base {

	public function get_name() {
		return 'ala-review';
	}

	public function get_title() {
		return __( 'Display Review', 'allaround-addons' );
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
				'label' => esc_html__( 'All Review', 'globalasst' ),
			]
		);

		$this->add_control(
            'ppp',
            [
                'label' => __('Number of review display', 'themepaw-companion'),
                'type' => Controls_Manager::NUMBER,
				'default' => ''
            ]
        );
		
		
		$this->end_controls_section();

	}

	protected function render() {

		$instance = $this->get_settings_for_display();

		$ppp = ! empty( $instance['ppp'] ) ? absint( $instance['ppp'] ) : 5;

        $review_args = array(
            'posts_per_page' => $ppp,
            'post_type'      => 'review',
            'post_status'    => 'publish',
            'order'          => 'DESC'
        );
        $review_qry  = new \WP_Query( $review_args );
        $found_reviews = $review_qry->found_posts;
        ?>
        <div class="alarnd--review-wrapper">
            <h2><?php esc_html_e( 'םישמתשמ בושמ', 'hello-elementor' ); ?></h2>
            <?php if ( $review_qry->have_posts() ) : ?>
            <div class="alarnd--review-groups" data-ppp="<?php echo esc_attr( $ppp ); ?>">
                <?php
                while ( $review_qry->have_posts() ) : $review_qry->the_post(); 
                
                $rating = get_post_meta( get_the_ID(), 'rating', true );
                $name = get_post_meta( get_the_ID(), 'name', true );
                $custom_date = get_post_meta( get_the_ID(), 'custom_date', true );
                $email = get_post_meta( get_the_ID(), 'email', true );
                $avatar = get_post_meta( get_the_ID(), 'avatar', true );
                $thumb = get_post_meta( get_the_ID(), 'review_thumb', true );

                $user_email = ! empty( $email ) ? $email : null;
                $gravatar = ! empty( $avatar ) ? '<img src="'.wp_get_attachment_url( (int) $avatar ).'"/>' : get_avatar( $user_email, 100, 'mystery' );
                $review_thumb = ! empty( $thumb ) ? '<a href="'.wp_get_attachment_url( (int) $thumb ).'"><img src="'.wp_get_attachment_url( (int) $thumb ).'"/></a>' : null;
                $user_name = ! empty( $name ) ? $name : esc_html__('Anonymous', 'hello-elementor');


                $the_date = ! empty( $custom_date ) ? date_i18n('j F ,Y', strtotime($custom_date)) : get_the_date( 'j F ,Y' );

                ?>
                <div class="alarnd--single-review">
                    <div class="review-item">
                        <div class="review-avatar">
                            <?php echo $gravatar; ?>
                        </div>
                        <div class="review-body">
                            <?php echo alarnd_single_review_avg( $rating ); ?>

                            <h4 class="review-title"><?php the_title(); ?></h4>

                            <div class="review-details">
                                <div class="review-avatar-mobile">
                                    <?php echo $gravatar; ?>
                                </div>
                                <span class="reviewer-name">
                                    <strong><?php echo $user_name; ?></strong>
                                </span>
                                <time class="review-date"><?php echo $the_date; ?></time>
                            </div>

                            <?php the_content(); ?>
                        </div>
						<div class="review-thumb">
                            <?php echo $review_thumb; ?>
						</div>
                    </div>
                </div>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            </div>
            <?php if( $found_reviews > $ppp ) : ?>
                <div class="alarnd--all-review-page">
                    <a href="#" class="alarn--load-review alarnd_simple_button">לחצו לביקורות נוספות</a>
                </div>
            <?php endif; ?>
            <?php else : ?>
                <p><?php esc_html_e( 'Sorry, no review found.', 'hello-elmentor' ); ?></p>
            <?php endif; ?>
        </div>
        <?php
	}

	protected function __content_template() {}

	public function render_plain_content( $instance = [] ) {}

}