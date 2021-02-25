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
	if ( get_site_option( 'initial_setup' ) < 2 ) {
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

		// Import the default themes.
		$default_themes = [
			'default',
			'classic',
			'twentyten',
			'twentyeleven',
			'twentytwelve',
			'twentythirteen',
			'twentyfourteen',
			'twentyfifteen',
			'twentysixteen',
			'twentyseventeen',
			'twentynineteen',
			'twentytwenty',
			'twentytwentyone',
		];
		foreach ( $default_themes as $theme ) {
			import_theme_from_wporg( $theme );
		}

		update_site_option( 'initial_setup', 2 );
	}

} );

function import_theme_from_wporg( $theme_slug ) {
	// Copied mostly from WPORG_Themes_Upload::create_or_update_theme_post()
	// TODO: Rewrite the Theme Directory to have this as a callable function.

	$args = [
		'action' => 'theme_information',
		'request' => [
			'slug' => $theme_slug,
			'fields' => [
				'description'     => true,
				'downloaded'      => true,
				'ratings'         => true,
				'template'        => true,
				'theme_url'       => true,
				'active_installs' => true,
			]
		]
	];

	// Query API directly rather than through themes_api() for reasons that will be clear later.
	$url = add_query_arg( $args, 'https://api.wordpress.org/themes/info/1.2/' );

	$theme = json_decode( file_get_contents( $url ) );

	$post_id = get_posts( [
		'post_type'   => 'repopackage',
		'name'        => $theme_slug,
		'post_status' => 'any'
	] );
	$post_id = $post_id ? $post_id[0]->ID : null;

	$post_id = wp_insert_post( array(
		'ID'                => $post_id,
		'post_title'        => $theme->name,
		'post_name'         => $theme_slug,
		'post_content'      => $theme->sections->description,
		'post_parent'       => 0,
		'post_date'         => $theme->last_updated,
		'post_date_gmt'     => $theme->last_updated,
		'post_modified'     => $theme->last_updated,
		'post_modified_gmt' => $theme->last_updated,
		'comment_status'    => 'closed',
		'ping_status'       => 'closed',
		'post_type'         => 'repopackage',
		'post_status'       => 'publish',
		'tags_input'        => array_keys( (array) $theme->tags ),
	) );

	// Finally, add post meta.
	$prefixed_post_meta = [
		'_theme_url'    => $theme->theme_url,
		'_author'       => $theme->author->author ?: $theme->author->display_name,
		'_author_url'   => $theme->author->author_url ?: $theme->author->profile,
		'_requires'     => $theme->requires,
		'_requires_php' => $theme->requires_php,
		'_upload_date'  => $theme->last_updated,
		'_ticket_id'    => 0,
		'_screenshot'   => basename( parse_url ( $theme->screenshot_url, PHP_URL_PATH ) ),
	];
	$post_meta = [
		'_active_installs' => $theme->active_installs,
		'_downloads'       => $theme->downloaded,
		'_popularity'      => 1 / rand( 1, 1000 ), // :)

		// for data shimming below.
		'downloads'        => (int) $theme->downloaded,
		'rating'           => (int) $theme->rating / 20, // convert % => 0-5
		'ratings'          => (array) $theme->ratings,
		'num_ratings'      => (int) $theme->num_ratings,
	];

	foreach ( $prefixed_post_meta as $meta_key => $meta_value ) {
		$meta_data = array_filter( (array) get_post_meta( $post_id, $meta_key, true ) );
		$meta_data[ $theme->version ] = $meta_value;
		update_post_meta( $post_id, $meta_key, $meta_data );
	}
	foreach ( $post_meta as $meta_key => $meta_value ) {
		update_post_meta( $post_id, $meta_key, $meta_value );
	}

	wporg_themes_update_version_status( $post_id, $theme->version, 'live' );
}

// Shim the ratings.
class WPORG_Ratings {
	static function get_rating_counts( $type, $theme ) {
		return get_posts( 'post_type=repopackage&post_status=any&name=' . $theme )[0]->ratings ?? [];
	}
	static function get_avg_rating( $type, $theme ) {
		return get_posts( 'post_type=repopackage&post_status=any&name=' . $theme )[0]->rating ?? 0;
	}
	static function get_rating_count( $type, $theme ) {
		return get_posts( 'post_type=repopackage&post_status=any&name=' . $theme )[0]->num_ratings ?? 0;
	}
}

// Shim the download counts.
add_filter( 'query', function( $query ) {
	global $wpdb;
	if ( preg_match( '!^SELECT.+FROM bb_themes_stats WHERE slug = (.+)$!i', $query, $m ) ) {
		$theme = trim( $m[1], '\'" ' );

		$count = get_posts( 'post_type=repopackage&post_status=any&name=' . $theme )[0]->downloads ?? 0;

		$query = $wpdb->prepare( "SELECT %d", $count );
	}

	return $query;
} );