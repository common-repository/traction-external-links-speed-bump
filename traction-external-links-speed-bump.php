<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	
/*
Plugin Name: Traction External Links Speed Bump
Description: Activates a speed bump on all external links and gives site owner the ability to enter a list of domains or specific links that when clicked will not trigger the external link speed bump.
Version:     1.9.6
Author:      Team Traction
Author URI:  https://tractionmarketing.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: traction-external-links-speed-bump
*/


/*
 *	Assign global variables
 *
*/

// $plugin_url = WP_PLUGIN_URL . '/traction-external-links-speed-bump';
$plugin_url = plugins_url(__FILE__);
$options = array();

/*
 *	Add a link to our plugin in the admin menu
 *	under 'Settings > Speed Bump'
 *
*/

function trelsb_menu() {

	/*
	 * 	Use the add_options_page function
	 * 	add_options_page( $page_title, $menu_title, $capability, $menu-slug, $function ) 
	 *
	*/

	add_options_page(
		'Traction External Links Speed Bump',
		'Speed Bump',
		'manage_options',
		'trelsb-options',
		'trelsb_options_page'
	);

}
add_action( 'admin_menu', 'trelsb_menu' );

/*
 *	Add default options to options table if 
 *	they don't exist.
 *
*/

function trelsb_activate() {

	if( !get_option( 'trelsb_options' )) {
		$defaults = array(
			'trelsb_ignored_domains' => '',
			'trelsb_ignored_links' => '',
			'trelsb_speedbump_text' => 'You are about to leave the current site',
			'trelsb_continue_text' => 'Continue',
			'trelsb_cancel_text' => 'Cancel'
		);
		
		update_option( 'trelsb_options', $defaults );
	}
}

register_activation_hook( __FILE__, 'trelsb_activate' );

/*
 *	Add a link to our plugin in the admin menu
 *	under 'Settings > Treehouse Badges'
 *
*/

function trelsb_options_page() {

	if( !current_user_can( 'manage_options' ) ) {

		wp_die( 'You do not have sufficient permissions to access this page.' );

	}
	
	global $plugin_url;
	global $options;
	
	function trelsb_allowed_html() {

		$allowed_tags = array(
			'a' => array(
				'class' => array(),
				'href'  => array(),
				'rel'   => array(),
				'title' => array(),
			),
			'abbr' => array(
				'title' => array(),
			),
			'b' => array(),
			'blockquote' => array(
				'cite'  => array(),
			),
			'cite' => array(
				'title' => array(),
			),
			'code' => array(),
			'del' => array(
				'datetime' => array(),
				'title' => array(),
			),
			'dd' => array(),
			'div' => array(
				'class' => array(),
				'title' => array(),
				'style' => array(),
			),
			'dl' => array(),
			'dt' => array(),
			'em' => array(),
			'h1' => array(),
			'h2' => array(),
			'h3' => array(),
			'h4' => array(),
			'h5' => array(),
			'h6' => array(),
			'i' => array(),
			'img' => array(
				'alt'    => array(),
				'class'  => array(),
				'height' => array(),
				'src'    => array(),
				'width'  => array(),
			),
			'li' => array(
				'class' => array(),
			),
			'ol' => array(
				'class' => array(),
			),
			'p' => array(
				'class' => array(),
			),
			'q' => array(
				'cite' => array(),
				'title' => array(),
			),
			'span' => array(
				'class' => array(),
				'title' => array(),
				'style' => array(),
			),
			'strike' => array(),
			'strong' => array(),
			'ul' => array(
				'class' => array(),
			),
		);
		
		return $allowed_tags;
	}
	
	$allowed_html = trelsb_allowed_html();
		
	if( isset( $_POST['trelsb_form_submitted'] ) ) {
		
		if ( ! empty( $_POST ) && check_admin_referer( 'trelsb_save_action', 'trelsb_nonce' ) ) {
			$hidden_field =  esc_html( $_POST['trelsb_form_submitted'] );
			
			if( $hidden_field == 'Y' ) {
				
				$trelsb_ignored_domains = sanitize_text_field( $_POST['trelsb_ignored_domains'] );
				$trelsb_ignored_links = esc_url_raw( $_POST['trelsb_ignored_links'] );
				$trelsb_speedbump_text = wpautop(stripslashes(wp_kses($_POST['trelsb_speedbump_text'], $allowed_html)));
				$trelsb_continue_text = sanitize_text_field( $_POST['trelsb_continue_text'] );
				$trelsb_cancel_text = sanitize_text_field( $_POST['trelsb_cancel_text'] );
				
				$options['trelsb_ignored_domains'] = $trelsb_ignored_domains;
				$options['trelsb_ignored_links'] = $trelsb_ignored_links;
				$options['trelsb_speedbump_text'] = $trelsb_speedbump_text;
				$options['trelsb_continue_text'] = $trelsb_continue_text;
				$options['trelsb_cancel_text'] = $trelsb_cancel_text;
				
				update_option( 'trelsb_options', $options );
				
			}
		}
	
	}
	
	$defaults = array(
		'trelsb_ignored_domains' => '',
		'trelsb_ignored_links' => '',
		'trelsb_speedbump_text' => 'You are about to leave the current site',
		'trelsb_continue_text' => 'Continue',
		'trelsb_cancel_text' => 'Cancel'
	);
	
	$options = wp_parse_args(get_option( 'trelsb_options', $defaults ));
	
	if( $options != '' ) {
		
		$trelsb_ignored_domains = $options['trelsb_ignored_domains'];
		$trelsb_ignored_links = $options['trelsb_ignored_links'];
		$trelsb_speedbump_text = $options['trelsb_speedbump_text'];
		$trelsb_continue_text = $options['trelsb_continue_text'];
		$trelsb_cancel_text = $options['trelsb_cancel_text'];	
	}
	
	require( 'inc/options-page-wrapper.php' );

}

/*
 *	Enqueue files for plugin.
 *
*/

function trelsb_add_scripts() {
	$plugin_url = plugin_dir_url( __FILE__ );
	
	wp_enqueue_style( 'trelsb_frontend_style', $plugin_url . 'css/traction-external-links-speed-bump.css');
	wp_enqueue_script( 'trelsb_frontend_script', $plugin_url . 'js/traction-external-links-speed-bump.js', array( 'jquery' ) );
	
	
	$options = get_option( 'trelsb_options' );
	
	if( $options != '' ) {
		
		$trelsb_ignored_domains = $options['trelsb_ignored_domains'];
		$trelsb_ignored_links = $options['trelsb_ignored_links'];
		$trelsb_speedbump_text = $options['trelsb_speedbump_text'];
		$trelsb_continue_text = $options['trelsb_continue_text'];
		$trelsb_cancel_text = $options['trelsb_cancel_text'];
		
	}
	
	$dataToBePassed = array(
		'domainExceptions'	=> $trelsb_ignored_domains,
		'linkExceptions'	=> $trelsb_ignored_links,
		'speedbumpText'	=> $trelsb_speedbump_text,
		'continueText'		=> $trelsb_continue_text,
		'cancelText'		=> $trelsb_cancel_text
	);
	
	wp_localize_script( 'trelsb_frontend_script', 'trelsb_php_vars', $dataToBePassed );
}
add_action( 'wp_enqueue_scripts', 'trelsb_add_scripts');