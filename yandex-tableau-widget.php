<?php
/*
Plugin Name: Yandex Tableau Widget
Plugin URI:  https://wordpress.org/plugins/yandex-tableau-widget/
Description: The Yandex Tableau Widget plugin allows to customize your WordPress site representation on the Yandex.Browser Tableau.
Version:     1.0
Author:      Artem Frolov
Author URI:  https://profiles.wordpress.org/dikiy_forester
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: yandex-tableau-widget
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

define( 'YTW_TD', 'yandex-tableau-widget' );

// Define widget in the Head section.
add_action( 'wp_head', 'afaytw_wp_head' );

// Setup the Theme Customizer settings and controls.
add_action( 'customize_register', 'afaytw_register' );

if ( is_admin() ) {
	load_plugin_textdomain( YTW_TD, false, basename( dirname( __FILE__ ) ) . '/languages' );
}

/**
 * Setup widget parameters.
 *
 * "logo" and "color" are mandatory parameters, if at least one of them is not
 * set, widget won't be generated.
 */
function afaytw_wp_head() {

	$args  = array();
	$logo  = get_option( 'afaytw_logo' );
	$color = get_option( 'afaytw_color' );
	$feed  = ''/** plugin_dir_url( __FILE__ ) . 'feed.json' */;

	if ( ! $logo || ! $color ) {
		return;
	}

	$args[] = 'logo=' . esc_url( $logo );
	$args[] = 'color=' . esc_attr( $color );

	if ( $feed ) {
		$args[] = 'feed=' . esc_url( $feed );
	}

	echo '<meta name="yandex-tableau-widget" content="' . implode( ', ', $args ) . '" />' . "\n";
}

/**
 * This hooks into 'customize_register' (available as of WP 3.4) and allows
 * to add new sections and controls to the Theme Customize screen.
 *
 * @see add_action('customize_register',$func)
 * @param \WP_Customize_Manager $wp_customize
 * @since MyTheme 1.0
 */
function afaytw_register( $wp_customize ) {

	$wp_customize->add_section( 'afaytw_widget', array(
		'title'       => __( 'Yandex Tableau Widget', YTW_TD ),
		'priority'    => 35,
		'description' => __( 'Allows you to customize Yandex Tableau Widget. Read more about <a href="https://tech.yandex.com/browser/tableau/doc/dg/concepts/create-widget-docpage/#widget-req">Widget design requirements</a>', YTW_TD ),
	) );

	$wp_customize->add_setting( 'afaytw_color', array(
		'default'    => get_background_color(),
		'type'       => 'option',
		'transport'  => 'postMessage',
	) );

	$wp_customize->add_setting( 'afaytw_logo', array(
		'type'       => 'option',
		'transport'  => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control(
		$wp_customize,
		'afaytw_color',
		array(
			'label'    => __( 'Widget Background Color', YTW_TD ),
			'section'  => 'afaytw_widget',
			'settings' => 'afaytw_color',
		)
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control(
		$wp_customize,
		'afaytw_logo',
		array(
			'label'       => __( 'Widget Logo', YTW_TD ),
			'description' => __( 'Select an image in PNG format.', YTW_TD ),
			'section'     => 'afaytw_widget',
			'settings'    => 'afaytw_logo',
			'mime_type'   => 'image/png',
		)
	) );
}