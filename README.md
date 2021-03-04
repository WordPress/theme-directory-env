# WordPress Theme Directory - Local development environment

## Prerequisites
- Git
- Docker
- Node/NPM

## Setup
1. `git clone https://github.com/wordpress/theme-directory-env`
2. `npm install`
3. `npm run start` (This will provision some git checkouts as needed)
4. Visit site at `localhost:8888`

## Stopping Environment
run `wp-env stop`

## Removing Environment
run `wp-env destroy`

## Development

This utilises the https://github.com/wordpress/theme-check and https://github.com/wordpress/wordpress.org Github repo's.

TODO. Add details about how to change origin of the repo's

TODO. Yarn might be able to build the themes?
