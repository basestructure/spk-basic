<?php
/**
 * SPK-BASIC
 * This file adds functions to the SPK-BASIC Theme.
 *
 * @package SPK-BASIC
 * @author  Mark Corpuz
 * @license GPL-2.0+
 * @link    http://markcorpuz.com/
 */

// Start the engine.
include_once( get_template_directory() . '/lib/init.php' );

// Setup Theme.
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

// Set Localization (do not remove).
add_action( 'after_setup_theme', 'setupbasic_localization_setup' );
function setupbasic_localization_setup(){
	load_child_theme_textdomain( 'base-starter', get_stylesheet_directory() . '/languages' );
}

// Add the helper functions.
include_once( get_stylesheet_directory() . '/lib/helper-functions.php' );

// Add Image upload and Color select to WordPress Theme Customizer.
require_once( get_stylesheet_directory() . '/lib/customize.php' );

// Include Customizer CSS.
include_once( get_stylesheet_directory() . '/lib/output.php' );

// Add WooCommerce support.
include_once( get_stylesheet_directory() . '/lib/woocommerce/woocommerce-setup.php' );

// Add the required WooCommerce styles and Customizer CSS.
include_once( get_stylesheet_directory() . '/lib/woocommerce/woocommerce-output.php' );

// Add the Genesis Connect WooCommerce notice.
include_once( get_stylesheet_directory() . '/lib/woocommerce/woocommerce-notice.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'SPK-BASIC' );
define( 'CHILD_THEME_URL', 'http://model.basestructure.com/spkbasic' );
define( 'CHILD_THEME_VERSION', '2.5.0.1' );

// Enqueue Scripts and Styles.
add_action( 'wp_enqueue_scripts', 'setupbasic_enqueue_scripts_styles' );
function setupbasic_enqueue_scripts_styles() {

	wp_enqueue_style( 'setupbasic-fonts', '//fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Roboto+Condensed:300,400,700|Lato:100,300,400,700,900', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'dashicons' );

	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	wp_enqueue_script( 'setupbasic-responsive-menu', get_stylesheet_directory_uri() . "/js/responsive-menus{$suffix}.js", array( 'jquery' ), CHILD_THEME_VERSION, true );
	wp_localize_script(
		'setupbasic-responsive-menu',
		'genesis_responsive_menu',
		setupbasic_responsive_menu_settings()
	);

}

// Define our responsive menu settings.
function setupbasic_responsive_menu_settings() {

	$settings = array(
		'mainMenu'          => __( 'Menu', 'setupbasic' ),
		'menuIconClass'     => 'dashicons-before dashicons-menu',
		'subMenu'           => __( 'Submenu', 'setupbasic' ),
		'subMenuIconsClass' => 'dashicons-before dashicons-arrow-down-alt2',
		'menuClasses'       => array(
			'combine' => array(
				'.nav-primary',
				'.nav-header',
			),
			'others'  => array(),
		),
	);

	return $settings;

}

// Add HTML5 markup structure.
add_theme_support( 'html5', array( 'caption', 'comment-form', 'comment-list', 'gallery', 'search-form' ) );

// Add Accessibility support.
add_theme_support( 'genesis-accessibility', array( '404-page', 'drop-down-menu', 'headings', 'rems', 'search-form', 'skip-links' ) );

// Add viewport meta tag for mobile browsers.
add_theme_support( 'genesis-responsive-viewport' );

// Add support for custom header.
add_theme_support( 'custom-header', array(
	'width'           => 600,
	'height'          => 160,
	'header-selector' => '.site-title a',
	'header-text'     => false,
	'flex-height'     => true,
) );

// Add support for custom background.
add_theme_support( 'custom-background' );

// Add support for after entry widget.
add_theme_support( 'genesis-after-entry-widget-area' );

// Add support for 3-column footer widgets.
add_theme_support( 'genesis-footer-widgets', 3 );

// Add Image Sizes.
add_image_size( 'featured-image', 720, 405, TRUE );

// Rename primary and secondary navigation menus.
add_theme_support( 'genesis-menus', array( 'primary' => __( 'Header Menu', 'setupbasic' ), 'secondary' => __( 'Top Menu', 'setupbasic' ) ) );

// Reposition the primary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_header_right', 'genesis_do_nav', 5 );

// Reposition the secondary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_before_header', 'genesis_do_subnav', 5 );

// Reduce the secondary navigation menu to one level depth.
add_filter( 'wp_nav_menu_args', 'setupbasic_secondary_menu_args' );
function setupbasic_secondary_menu_args( $args ) {

	if ( 'secondary' != $args['theme_location'] ) {
		return $args;
	}

	$args['depth'] = 1;

	return $args;

}

// Modify size of the Gravatar in the author box.
add_filter( 'genesis_author_box_gravatar_size', 'setupbasic_author_box_gravatar' );
function setupbasic_author_box_gravatar( $size ) {
	return 90;
}

// Modify size of the Gravatar in the entry comments.
add_filter( 'genesis_comment_list_args', 'setupbasic_comments_gravatar' );
function setupbasic_comments_gravatar( $args ) {

	$args['avatar_size'] = 60;

	return $args;

}

//* Add the credits section on the site footer
remove_action( 'genesis_footer', 'genesis_do_footer' );
add_action( 'genesis_footer', 'swp_sitefooter_credit' );
function swp_sitefooter_credit() {
	?>
	<div class="siteby"><a href="http://smarterwebpackages.com/">SmarterWebPackages.com</a></div>
	<div class="copyright">Copyright © <?php echo date("Y"); ?> Smarter Better · All Rights Reserved<br>
	Site Design by <a href="http://smarterwebpackages.com/">SmarterWebPackages.com</a></div>
	<?php
}