#!/bin/bash

# This ideally wouldn't be required, but we need some SVN checkouts of certain paths, and creation of some global things.

# Maybe checkout the plugin/themes
[[ -d wp-content/mu-plugins/pub ]] || svn co -q --non-interactive https://meta.svn.wordpress.org/sites/trunk/wordpress.org/public_html/wp-content/mu-plugins/pub/ wp-content/mu-plugins/pub
[[ -d wp-content/plugins/theme-directory ]] || svn co -q --non-interactive https://meta.svn.wordpress.org/sites/trunk/wordpress.org/public_html/wp-content/plugins/theme-directory/ wp-content/plugins/theme-directory
[[ -d wp-content/themes/wporg ]] || svn co -q --non-interactive https://meta.svn.wordpress.org/sites/trunk/wordpress.org/public_html/wp-content/themes/pub/wporg/ wp-content/themes/wporg
[[ -d wp-content/themes/wporg-themes ]] || svn co -q --non-interactive https://meta.svn.wordpress.org/sites/trunk/wordpress.org/public_html/wp-content/themes/pub/wporg-themes/ wp-content/themes/wporg-themes

# Maybe add the supporting plugins. wp-env doesn't have the ability to pull from WordPress.org.
for plugin in jetpack theme-check query-monitor; do
	[[ -d wp-content/plugins/$plugin ]] || svn co -q --non-interactive https://plugins.svn.wordpress.org/$plugin/trunk/ wp-content/plugins/$plugin
done

svn up wp-content/{mu-plugins,plugins,themes}/*