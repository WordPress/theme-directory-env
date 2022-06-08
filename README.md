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

TODO. Add details about how to change origin of the repo's

TODO. Yarn might be able to build the themes?
