<?php
namespace GlobalAssistant;

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Search extends Widget_Base
{

    public function get_name()
    {
        return 'ala-search';
    }

    public function get_title()
    {
        return __('Search Form', 'allaround-addons');
    }

    public function get_categories()
    {
        return array('allaroundwidget');
    }

    public function get_icon()
    {
        return 'eicon-allaround-icon';
    }

    protected function register_controls() { 

        $this->start_controls_section(
            'content_section',
            [
            'label' => esc_html__( 'Content', 'allaround-addons' ),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        
        
        $this->add_control(
            'search_box_type',
            [
                'label' => __('Only Icon', 'allaround-addons'),
                'type' => Controls_Manager::SWITCHER,
            	'label_on' => esc_html__( 'Yes', 'allaround-addons' ),
            	'label_off' => esc_html__( 'No', 'allaround-addons' ),
            	'return_value' => 'yes',
            	'default' => 'yes',
            ]
        );
        
        $this->add_control(
        	'placeholder_title',
        	[
            	'label' => esc_html__( 'Placeholder Text', 'allaround-addons' ),
            	'type' => \Elementor\Controls_Manager::TEXT,
            	'label_block' => true,
            	'default' => esc_html__( 'לחפש', 'allaround-addons' ),
            	'placeholder' => esc_html__( 'לחפש', 'allaround-addons' ),
                'condition'   => [
    				'search_box_type' => '',
    			]
        	]
        );
        
		$this->add_control(
			'icon',
			[
				'label' => esc_html__( 'Search Icon', 'allaround-addons' ),
				'type' => \Elementor\Controls_Manager::ICON,
				'default' => 'fa fa-search',
                'condition'   => [
    				'search_box_type' => 'yes',
    			]
			]
		);

        $this->end_controls_section();

    }

    protected function render() {

    // get our input from the widget settings.
    $settings = $this->get_settings_for_display();
	
	// get the individual values of the input
	$placeholder = $settings['placeholder_title'];
	$searchicon = $settings['icon'];

	?>
    <?php if( !empty( $searchicon ) ) : ?>
        <div class="elem_widget_search_input alr_search_icon">
            <input type="text" class="elem_input_search">
            <i class="<?php echo $searchicon; ?>" aria-hidden="true"></i>
        </div>
    <?php else : ?>
        <div class="elem_widget_search_input">
            <input type="text" placeholder="<?php echo $placeholder;  ?>" class="elem_input_search">
        </div>
    <?php endif; ?>
    <?php 

}



}