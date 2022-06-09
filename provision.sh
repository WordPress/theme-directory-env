#!/bin/bash

# This ideally wouldn't be required, but we need some Git checkouts, and submodules are not quite what we need.

# We need a checkout of meta
[[ -d meta.git ]] || git clone https://github.com/wordpress/wordpress.org meta.git

# We need a checkout of theme-check
[[ -d wp-content/plugins/theme-check ]] || git clone https://github.com/wordpress/theme-check wp-content/plugins/theme-check

# we need the wporg-mu-plugins for headers.
[[ -d wporg-mu-plugins.git ]] || git clone --branch build https://github.com/WordPress/wporg-mu-plugins wporg-mu-plugins.git
