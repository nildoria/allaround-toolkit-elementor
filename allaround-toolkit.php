<?php

/**
 * Plugin Name: AllAround Toolkit
 * Description: Custom element added to Elementor
 * Version: 1.0.5
 * Author: msumon
 * Author URI: http://themepaw.com
 * Text Domain: elementor-custom-element
 */


if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * Main AllAround Toolkit Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
final class AllAround_Extension
{

	/**
	 * Plugin Version
	 *
	 * @since 1.0.1
	 *
	 * @var string The plugin version.
	 */
	const VERSION = '1.0.2';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.1
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.5.11';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '6.0';

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @var Globalasst_Extension The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return Globalasst_Extension An instance of the class.
	 */
	public static function instance()
	{

		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */

	public function __construct()
	{
		add_action('init', [$this, 'i18n']);
		add_action('plugins_loaded', [$this, 'init']);
	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 *
	 * Fired by `init` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function i18n()
	{
	}

	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.1
	 *
	 * @access public
	 */
	public function init()
	{

		// Check if Elementor installed and activated
		if (!did_action('elementor/loaded')) {
			add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
			return;
		}

		// Check for required Elementor version			
		if (!version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
			add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
			return;
		}

		// Check for required PHP version
		if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
			add_action('admin_notices', [$this, 'admin_notice_minimum_php_version']);
			return;
		}

		// Add Plugin actions
		add_action('elementor/widgets/widgets_registered', [$this, 'init_widgets']);
		add_action('elementor/controls/controls_registered', [$this, 'init_controls']);
		add_action('elementor/init', array($this, 'add_elementor_category'));

		// Register Widget Styles
		add_action('wp_enqueue_scripts', [$this, 'frontend_styles',], 10);
		add_action('elementor/frontend/after_enqueue_styles', [$this, 'el_frontend_styles'], 40);
		add_action('elementor/editor/after_enqueue_styles', [$this, 'widget_styles']);

	}

	public function add_elementor_category()
	{
		\Elementor\Plugin::instance()->elements_manager->add_category('allaroundwidget', array(
			'title' => __('AllAround Widgets', 'themepaw-companion'),
			'icon' => 'allaround_icon',
		), 10);
	}

	public function widget_styles()
	{
		//For Example
		wp_enqueue_style('allaround-widgets', plugins_url('/css/widgets.css', __FILE__));
	}

	public function frontend_styles()
	{
		//For Example
		wp_register_style('allaround-frontend', plugins_url('/css/frontend.css', __FILE__));
		wp_enqueue_style('swiper_css', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/6.8.4/swiper-bundle.min.css', array());
		wp_enqueue_script('swiper-script', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/6.8.4/swiper-bundle.min.js', array('jquery'), '6.8.4', true);
	}

	/**
	 * Elementor hook to load frontend styles
	 *
	 * @return void
	 */
	public function el_frontend_styles()
	{
		wp_enqueue_style('allaround-frontend');
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_missing_main_plugin()
	{

		if (isset($_GET['activate']))
			unset($_GET['activate']);

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'globalasst'),
			'<strong>' . esc_html__('AllAround Widgets', 'globalasst') . '</strong>',
			'<strong>' . esc_html__('Elementor', 'globalasst') . '</strong>'
		);

		printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version()
	{

		if (isset($_GET['activate']))
			unset($_GET['activate']);

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'globalasst'),
			'<strong>' . esc_html__('AllAround Widgets', 'globalasst') . '</strong>',
			'<strong>' . esc_html__('Elementor', 'globalasst') . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_php_version()
	{

		if (isset($_GET['activate']))
			unset($_GET['activate']);

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'globalasst'),
			'<strong>' . esc_html__('AllAround Widgets', 'globalasst') . '</strong>',
			'<strong>' . esc_html__('PHP', 'globalasst') . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);

	}

	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init_widgets()
	{
		// For Example
		// Include Widget files
		require_once (__DIR__ . '/widgets/category.php');
		require_once (__DIR__ . '/widgets/review.php');
		require_once (__DIR__ . '/widgets/menucart.php');
		require_once (__DIR__ . '/widgets/readmore.php');
		require_once (__DIR__ . '/widgets/carousel-list.php');
		require_once (__DIR__ . '/widgets/carousel-cart.php');
		require_once (__DIR__ . '/widgets/search.php');
		require_once (__DIR__ . '/widgets/carousel.php');
		require_once (__DIR__ . '/widgets/uses-products.php');

		// Register widget
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \GlobalAssistant\Review());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \GlobalAssistant\Product_category());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \GlobalAssistant\Menu_Cart());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \GlobalAssistant\Read_More());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \GlobalAssistant\Carousel_List());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \GlobalAssistant\Carousel_Cart());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \GlobalAssistant\Search());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \GlobalAssistant\Carousel());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \GlobalAssistant\Uses_Products());

	}

	/*
	 * Init Controls
	 *
	 * Include controls files and register them
	 *
	 * @since 1.0.0				
	 *
	 * @access public
	 */
	public function init_controls()
	{
		//For example

		//Include Control files
		//require_once( __DIR__ . '/controls/multi-unit.php' );

		// Register control
		//\Elementor\Plugin::$instance->controls_manager->register_control( 'spicy-multi-unit-control', new spicy_multi_unit());

	}

}

AllAround_Extension::instance();


