<?php
/*
 * Registers, Renders and Validates all Options.
 */

function gv_pinfeed_options_init() {

	register_setting(
		'gv_pinfeed_options', // Options group
		'gv_pinfeed_options' // Database option
	);
}

add_action( 'admin_init', 'gv_pinfeed_options_init' );

/**
 * Change the capability required to save the 'gv_pinfeed_options' options group.
 */
function gv_pinfeed_option_capability( $capability ) {

	return 'manage_options';
}

add_filter( 'option_page_capability_gv_pinfeed_options', 'gv_pinfeed_option_capability' );

/**
 * Returns the options array for the 'gv_pinfeed_options' option group.
 */
function gv_pinfeed_get_options() {

	$saved = (array) get_option( 'gv_pinfeed_options' );

	$defaults = apply_filters( 'gv_pinfeed_get_options', $defaults );

	$options = wp_parse_args( $saved, $defaults );
	$options = array_intersect_key( $options, $defaults );

	return $options;
}

/**
 * Sanitize and validate form input. Accepts an array, return a sanitized array.
 */
function gv_pinfeed_options_validate( $input ) {

	$options = gv_pinfeed_get_options();

	$output = array();

	return apply_filters( 'gv_pinfeed_options_validate', $output, $options );
}
