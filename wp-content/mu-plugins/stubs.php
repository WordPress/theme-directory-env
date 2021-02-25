<?php
/**
 * Plugin Name: WordPress.org Stubs
 * Description: This plugin contains any stubs for other WordPress.org systems required to make the Theme Directory work.
 */

/**
 * Initial setup of the site, enable the plugins as wp-env doesn't do it.
 */
add_action( 'admin_init', function() {
	global $wpdb;
	if ( get_site_option( 'initial_setup' ) < 1 ) {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		activate_plugins( [
			'jetpack/jetpack.php',
			'theme-check/theme-check.php',
			'theme-directory/theme-directory.php'
		] );

		update_option( 'posts_per_page', 12 );

		// Initial pages are created by wporg_themes_activate().

		// Add example commercial shops.
		if ( ! get_posts( [ 'post_type' => 'theme_shop', 'post_status' => 'any' ] ) ) {
			foreach ( [ 'example.com', 'example.net', 'example.org' ] as $shop ) {
				wp_insert_post( [
					'post_type' => 'theme_shop',
					'post_status' => 'publish',
					'post_title' => $shop,
					'post_content' => "$shop $shop $shop\nExample Example Example\n$shop $shop $shop",
					'meta_input' => [
						'url' => "https://$shop/",
					],
				] );
			}
		}

		update_site_option( 'initial_setup', 1 );
	}

} );