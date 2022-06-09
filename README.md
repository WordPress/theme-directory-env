# WordPress Theme Directory - Local development environment

## Prerequisites
- Git
- Docker
- Node/NPM

## Setup
1. `git clone https://github.com/wordpress/theme-directory-env`
2. `cd theme-directory-env`
3. `npm install`
4. `npm run start` (This will provision some git checkouts as needed)
5. Visit site at <a href="http://localhost:8888" target="_blank">http://localhost:8888</a>

## Stopping Environment
`npm run wp-env stop`

## Removing Environment
`npm run wp-env destroy`

## Development

This utilises the https://github.com/wordpress/theme-check and https://github.com/wordpress/wordpress.org Github repo's.

You'll find the sources for the Theme Directory here:
 - ./meta.git/wordpress.org/public_html/wp-content/plugins/theme-directory/
 - ./meta.git/wordpress.org/public_html/wp-content/themes/pub/wporg-themes/

For Development of the Theme Directory, you'll need to run `npm install` within the wporg-themes folder, and `grunt` (or `./node_modules/grunt/bin/grunt`) to build JS/CSS

### Submitting PRs
By default this clones https://github.com/WordPress/wordpress.org, to submit PRs against that, you'll first need to fork that repo to your own GitHub account, and then add it as a remote to meta.git.

The simplest way is to switch the local clone to use your fork of the repo, instead of WordPress/wordpress.org directly:
`git remote set-url origin https://github.com/$USERNAME/wordpress.org`

Alternatively, you can add your fork as a separate remote:
`git remote add fork https://github.com/$USERNAME/wordpress.org`
And then when pushing your branch to your repo, use `git push --set-upstream fork $BRANCH`

For any changes you wish to submit, create a ticket in https://meta.trac.wordpress.org/ in the 'Theme Directory' component and then push a PR to https://github.com/WordPress/wordpress.org with a link to the ticket in the description.
