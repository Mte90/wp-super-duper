<?php
/**
 * This is a Hello World test plugin for WP Super Duper Class.
 *
 * @wordpress-plugin
 * Plugin Name: Super Duper - Hello World
 * Description: This is a Hello World test plugin for WP Super Duper Class.
 * Version: 0.0.1
 * Author: AyeCode
 * Author URI: https://ayecode.io
 * Text Domain: hello-world
 * Domain Path: /languages
 * Requires at least: 4.2
 * Tested up to: 5.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_Super_Duper' ) ) {
	// include the class if needed
	include_once( dirname( __FILE__ ) . "/wp-super-duper.php" );
}

class Hello_World extends WP_Super_Duper {


	public $arguments;

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {

		$options = array(
			'textdomain'     => 'hello-world',
			// textdomain of the plugin/theme (used to prefix the Gutenberg block)
			'block-icon'     => 'admin-site',
			// Dash icon name for the block: https://developer.wordpress.org/resource/dashicons/#arrow-right
			'block-category' => 'common',
			// the category for the block, 'common', 'formatting', 'layout', 'widgets', 'embed'.
			'block-keywords' => "['hello','world']",
			// used in the block search, MAX 3
//			'block-output'   => array( // the block visual output elements as an array
//				'element::p' => array(
//					'title'   => __( 'Placeholder', 'hello-world' ),
//					'class'   => '[%className%]',
//					'content' => 'Hello: [%after_text%]' // block properties can be added by wrapping them in [%name%]
//				)
//			),
			'block-output'   => array( // the block visual output elements as an array
				array(
					'element' => 'p',
					'title'   => __( 'Placeholder', 'hello-world' ),
					'class'   => '[%className%]',
					'content' => 'Hello: [%after_text%]' // block properties can be added by wrapping them in [%name%]
				)
			),
			'class_name'     => __CLASS__,
			// The calling class name
			'base_id'        => 'hello_world',
			// this is used as the widget id and the shortcode id.
			'name'           => __( 'Hello World', 'hello-world' ),
			// the name of the widget/block
			'widget_ops'     => array(
				'classname'   => 'hello-world-class',
				// widget class
				'description' => esc_html__( 'This is an example that will take a text parameter and output it after `Hello:`.', 'hello-world' ),
				// widget description
			),
			'arguments'      => array( // these are the arguments that will be used in the widget, shortcode and block settings.
				'after_text' => array( // this is the input name=''
					'title'       => __( 'Text after hello:', 'hello-world' ),
					// input title
					'desc'        => __( 'This is the text that will appear after `Hello:`.', 'hello-world' ),
					// input description
					'type'        => 'text',
					// the type of input, test, select, checkbox etc.
					'placeholder' => 'World',
					// the input placeholder text.
					'desc_tip'    => true,
					// if the input should show the widget description text as a tooltip.
					'default'     => 'World',
					// the input default value.
					'advanced'    => false
					// not yet implemented
				),
			)
		);

		parent::__construct( $options );
	}


	/**
	 * This is the output function for the widget, shortcode and block (front end).
	 *
	 * @param array $args The arguments values.
	 * @param array $widget_args The widget arguments when used.
	 * @param string $content The shortcode content argument
	 *
	 * @return string
	 */
	public function output( $args = array(), $widget_args = array(), $content = '' ) {

		/**
		 * @var string $after_text
		 * @var string $another_input This is added by filter below.
		 */
		extract( $args, EXTR_SKIP );

		/*
		 * This value is added by filter so might not exist if filter is removed so we check.
		 */
		if ( ! $another_input ) {
			$another_input = '';
		}

		return "Hello: " . $after_text . "" . $another_input;

	}

}

// register it.
add_action( 'widgets_init', function () {
	register_widget( 'Hello_World' );
} );


/**
 * Extend the options via filter hook, this can be done via plugin/theme.
 *
 * @param $options
 *
 * @return mixed
 */
function _my_extra_arguments( $options ) {

	/*
	 * Add a new input option.
	 */
	$options['arguments']['another_input'] = array(
		'name'        => 'another_input', // this is the input name=''
		'title'       => __( 'Another input:', 'hello-world' ), // input title
		'desc'        => __( 'This is an input added via filter.', 'hello-world' ), // input description
		'type'        => 'text', // the type of input, test, select, checkbox etc.
		'placeholder' => 'Placeholder text', // the input placeholder text.
		'desc_tip'    => true, // if the input should show the widget description text as a tooltip.
		'default'     => '', // the input default value.
		'advanced'    => false // not yet implemented
	);

	/*
	 * Output the new option in the block output also.
	 */
	$options['block-output']['element::p']['content'] = $options['block-output']['element::p']['content'] . " [%another_input%]";;

	return $options;
}

//add_filter( 'wp_super_duper_options_hello_world', '_my_extra_arguments' );