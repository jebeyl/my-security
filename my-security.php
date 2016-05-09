<?php
/**
 * Plugin Name: My Security
 * Description: Hide stuff for security reasons
 * Author: Spiros Kosmas
 */

add_action('init','jeb_my_security_initialization');
function jeb_my_security_initialization(){
		add_filter('the_generator', 'jeb_remove_version');
		
}


/*Hides WP Version*/
function jeb_remove_version() {
	return '';
}


/*Disables access to dashboard through /login and /admin */
function custom_wp_redirect_admin_locations() {
    global $wp_rewrite;
    if ( ! ( is_404() && $wp_rewrite->using_permalinks() ) )
        return;

    $admins = array(
        home_url( 'wp-admin', 'relative' ),
        home_url( 'dashboard', 'relative' ),
        home_url( 'admin', 'relative' ),
        site_url( 'dashboard', 'relative' ),
        site_url( 'admin', 'relative' ),
    );
    if ( in_array( untrailingslashit( $_SERVER['REQUEST_URI'] ), $admins ) ) {
        wp_redirect( admin_url() );
        exit;
    }

    $logins = array(
        home_url( 'wp-login.php', 'relative' )
    );
	if ( in_array( untrailingslashit( $_SERVER['REQUEST_URI'] ), $logins ) ) {
		wp_redirect( wp_login_url() );
		exit;
	}
}

function remove_default_login_redirect() {
    remove_action('template_redirect', 'wp_redirect_admin_locations', 1000);
    add_action( 'template_redirect', 'custom_wp_redirect_admin_locations', 1000 );
}

add_action('init','remove_default_login_redirect');

/*

//for yoat seo admin error
remove_action( 'wp_enqueue_scripts', 'wpseo_admin_bar_style' );
remove_action( 'admin_enqueue_scripts', 'wpseo_admin_bar_style' );

add_action( 'wp_enqueue_scripts', 'wpseo_admin_bar_style2' );
add_action( 'admin_enqueue_scripts', 'wpseo_admin_bar_style2' );


function wpseo_admin_bar_style2() {
	require_once(ABSPATH . 'wp-admin/includes/screen.php');
	$enqueue_style = false;

	// Single post in the frontend.
	if ( ! is_admin() && is_admin_bar_showing() ) {
		$enqueue_style = ( is_singular() || is_category() );
	}

	// Single post in the backend.
	if ( is_admin() ) {
		$screen = get_current_screen();

		// Post (every post_type) or category page.
		if ( 'post' === $screen->base || 'edit-tags' === $screen->base ) {
			$enqueue_style = true;
		}
	}

	if ( $enqueue_style ) {

		$asset_manager = new WPSEO_Admin_Asset_Manager();
		$asset_manager->register_assets();

		$asset_manager->enqueue_style( 'adminbar' );
	}
}
*/

