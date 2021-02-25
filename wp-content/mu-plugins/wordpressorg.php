<?php
/**
 * Plugin Name: WordPress.org Hosting
 */

/**
 * Include the WordPress.org pub mu-plugins.
 */
foreach ( glob( __DIR__  . '/pub/*.php' ) as $_pub_file ) {
	include_once $_pub_file;
}

/**
 * Avoid database errors by skipping certain tables that are not needed.
 */
add_filter( 'query', function( $query ) {
	// The wporg_locales table doesn't exist.
	if ( stripos( $query, "FROM wporg_locales" ) ) {
		return false;
	}

	return $query;
} );