<?php
namespace GlobalAssistant;

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

class Carousel_Cart extends Widget_Base
{

	public function get_name()
	{
		return 'ala-carousel-cart';
	}

	public function get_title()
	{
		return __('Carousel for Cart', 'allaround-addons');
	}

	public function get_categories()
	{
		return array('allaroundwidget');
	}

	public function get_icon()
	{
		return 'eicon-allaround-icon';
	}

	public function get_style_depends()
	{
		return array('my-custom-widget-style');
	}

	protected function register_controls()
	{

		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__('Content', 'textdomain'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'list',
			[
				'label' => esc_html__('List', 'textdomain'),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => [
					[
						'name' => 'image',
						'label' => esc_html__('Choose Image', 'textdomain'),
						'type' => \Elementor\Controls_Manager::MEDIA,
						'default' => [
							'url' => \Elementor\Utils::get_placeholder_image_src(),
						],
					],
					[
						'name' => 'text',
						'label' => esc_html__('Text', 'textdomain'),
						'type' => \Elementor\Controls_Manager::TEXT,
						'placeholder' => esc_html__('List Item', 'textdomain'),
						'default' => esc_html__('List Item', 'textdomain'),
					],
					[
						'name' => 'link',
						'label' => esc_html__('Link', 'textdomain'),
						'type' => \Elementor\Controls_Manager::URL,
						'placeholder' => esc_html__('https://your-link.com', 'textdomain'),
					],
				],
				'default' => [
					[
						'text' => esc_html__('List Item #1', 'textdomain'),
						'link' => 'https://allaround.co.il/',
					],
					[
						'text' => esc_html__('List Item #2', 'textdomain'),
						'link' => 'https://allaround.co.il/',
					],
				],
				'title_field' => '{{{ text }}}',
			]
		);

		$this->add_control(
			'show_navigation',
			[
				'label' => __('Show Navigation', 'allaround-addons'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'allaround-addons'),
				'label_off' => esc_html__('No', 'allaround-addons'),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_control(
			'show_pagination',
			[
				'label' => __('Show Pagination', 'allaround-addons'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'allaround-addons'),
				'label_off' => esc_html__('No', 'allaround-addons'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->end_controls_section();

	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();
		$navigation = $settings['show_navigation'];
		$pagination = $settings['show_pagination'];
		?>
		<div class="al-carousel-list-container alrnd-carousel-cart swiper-container">
			<div class="swiper-wrapper">
				<?php foreach ($settings['list'] as $index => $item): ?>
					<div class="carousel-item swiper-slide">
						<?php
						if (!$item['link']['url']) {
							// Get image by id
							echo '<a class="alCarouselList-url" href="#">';
							echo '<div class="alCarouselImg">' . wp_get_attachment_image($item['image']['id'], 'medium') . '</div>';
							echo '<span class="carousel-item_text">' . $item['text'] . '</span>';
							echo '</a>';
						} else {
							?>
							<a class="alCarouselList-url" href="<?php echo esc_url($item['link']['url']); ?>">
								<div class="alCarouselImg">
									<?php echo wp_get_attachment_image($item['image']['id'], 'medium'); ?>
								</div>
								<span class="carousel-item_text"><?php echo $item['text']; ?></span>
							</a>
							<?php
						}
						?>
					</div>
				<?php endforeach; ?>
			</div>
			<?php if (!empty($navigation)): ?>
				<div class="list-swiper-button-prev"></div>
				<div class="list-swiper-button-next"></div>
			<?php endif; ?>
			<?php if (!empty($pagination)): ?>
				<div class="swiper-pagination"></div>
			<?php endif; ?>
		</div>
		<script>
			jQuery(document).ready(function ($) {
				// Swiper: Slider
				var swiperList = new Swiper('.al-carousel-list-container', {
					loop: false,
					pagination: {
						el: '.swiper-pagination1',
						clickable: true,
					},
					navigation: {
						nextEl: '.list-swiper-button-next',
						prevEl: '.list-swiper-button-prev',
					},
					slidesPerView: 6,
					paginationClickable: true,
					spaceBetween: 20,
					// autoplay:
					// {
					// 	delay: 2500,
					// 	disableOnInteraction: true,
					// 	reverseDirection: true,
					// },
					direction: 'horizontal',
					breakpoints: {
						1920: {
							slidesPerView: 6,
							spaceBetween: 20
						},
						1028: {
							slidesPerView: 5,
							spaceBetween: 20
						},
						767: {
							slidesPerView: 4,
							spaceBetween: 10
						},
						150: {
							slidesPerView: 2,
							spaceBetween: 10
						}
					}
				});
			});
		</script>
		<?php
	}

}