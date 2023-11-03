<?php
namespace GlobalAssistant;

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Read_More extends Widget_Base
{

    public function get_name()
    {
        return 'ala-readmore';
    }

    public function get_title()
    {
        return __('Read More', 'allaround-addons');
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
        	'expand_title',
        	[
        	'label' => esc_html__( 'Expand text', 'allaround-addons' ),
        	'type' => \Elementor\Controls_Manager::TEXT,
        	'label_block' => true,
        	'default' => esc_html__( 'קרא/י עוד...', 'allaround-addons' ),
        	'placeholder' => esc_html__( 'קרא/י עוד...', 'allaround-addons' ),
        	]
        );
        
        $this->add_control(
        	'collaps_title',
        	[
        	'label' => esc_html__( 'Collaps text', 'allaround-addons' ),
        	'type' => \Elementor\Controls_Manager::TEXT,
        	'label_block' => true,
        	'default' => esc_html__( 'סגור טקסט', 'allaround-addons' ),
        	'placeholder' => esc_html__( 'סגור טקסט', 'allaround-addons' ),
        	]
        );

        $this->add_control(
            'readmore_content',
            [
            'label' => esc_html__( 'Content', 'allaround-addons' ),
            'type' => \Elementor\Controls_Manager::WYSIWYG,
            'label_block'   => true,
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {

    // get our input from the widget settings.
    $settings = $this->get_settings_for_display();
	
	// get the individual values of the input
	$readmore_content = $settings['readmore_content'];
	$expand = $settings['expand_title'];
	$collaps = $settings['collaps_title'];

	?>

        <!-- Start rendering the output -->
        <div class="alr_readmore">
            <details>
                <summary>
                <span id="open"><?php echo $expand;  ?></span> 
                <span id="close"><?php echo $collaps;  ?></span> 
                </summary>
                <div class="content">
                    <?php echo $readmore_content;  ?>
                </div>
            </details>
        </div>
        <!-- End rendering the output -->

        <?php

}



}