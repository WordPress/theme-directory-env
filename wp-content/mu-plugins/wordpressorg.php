<?php
/**
 * Plugin Name: WordPress.org Hosting
 */

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
