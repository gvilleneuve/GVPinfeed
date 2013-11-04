<?

/*
 * Plugin Name: GV Pinfeed
 * Plugin URI: http://wordpress.org/plugins/GVPinfeed/
 * Description: Connect your site to your Pinterest account and display your pins on your website.
 * Version: 1.0.0
 * Author: Geoff Villeneuve
 * Author URI: http://gffvllnv.com
 * Text Domain: gv_pinfeed
 * Domain Path: /languages
 */

if ( ! defined( 'GV_PINFEED_PLUGIN_VERSION' ) ) {
	define( 'GV_PINFEED_PLUGIN_VERSION', '1.0.0' );
}
if ( ! defined( 'GV_PINFEED_PLUGIN_URL' ) ) {
	define( 'GV_PINFEED_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
if ( ! defined( 'GV_PINFEED_PLUGIN_PATH' ) ) {
	define( 'GV_PINFEED_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

function gv_pinfeed_plugin_setup() {

	/**
	 * Include Plugin Options.
	 */
	require_once( GV_PINFEED_PLUGIN_PATH . 'options.php' );

	/**
	 * Include Menu Page.
	 */
	//require_once( GV_PINFEED_PLUGIN_PATH . 'inc/menu.php' );

	/**
	 * Include Custom Widget.
	 */
	require_once( GV_PINFEED_PLUGIN_PATH . 'widget.php' );
	/**
	 * Include Request for the Twitter Feed.
	 */
	//require_once( GV_PINFEED_PLUGIN_PATH . 'inc/get_tweets.php' );

	/**
	 * Include Shortcode.
	 */
	//require_once( GV_PINFEED_PLUGIN_PATH . 'inc/shortcode.php' );

	/**
	 * Load Text Domain for Translations.
	 */
	//load_plugin_textdomain( 'gv_pinfeed', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

}

add_action( 'plugins_loaded', 'gv_pinfeed_plugin_setup', 15 );

if ( ! function_exists( 'gv_pinfeed_plugin_scripts' ) ):

	/**
	 * Enqueue plugin scripts and styles.
	 */
	function gv_pinfeed_scripts() {

		// Queues the main CSS file.
		wp_register_style(
			'gv-pinfeed-plugin',
				GV_PINFEED_PLUGIN_URL . 'pin.css',
			array(),
			GV_PINFEED_PLUGIN_VERSION,
			'all'
		);
		wp_register_style(
			'gv-pinfeed-plugin-default-style',
				GV_PINFEED_PLUGIN_URL . 'pin-default.css',
			array(),
			GV_PINFEED_PLUGIN_VERSION,
			'all'
		);

		// Queues the js to make the slider happen
		wp_register_script( 'jquery-latest', '//code.jquery.com/jquery-latest.min.js', array(), null, false );
		wp_register_script( 'unslider', '//unslider.com/unslider.min.js', array(), null, false );
		wp_register_script( 'pin-widget-js', '/wp-content/plugins/GVPinfeed/pin.js', array(), null, false );

		// Enqueue Stylesheet for Admin Pages
		if ( is_admin() ) {
			wp_enqueue_style( 'gv-pinfeed-plugin' );
		}
	}

	add_action( 'wp_enqueue_scripts', 'gv_pinfeed_scripts' );
	add_action( 'admin_enqueue_scripts', 'gv_pinfeed_scripts' );

endif;

/**
 * Add a link to the plugin screen, to allow users to jump straight to the settings page.
 */
function gv_pinfeed_plugin_meta( $links ) {

	$links[] = '<a href="' . admin_url( 'options-general.php?page=gv-pinfeed' ) . '">' . __(
				'Settings',
				'gv_pinfeed'
			) . '</a>';

	return $links;
}

add_filter( 'plugin_action_links_gv-pinfeed/gv-pinfeed.php', 'gv_pinfeed_plugin_meta' );
