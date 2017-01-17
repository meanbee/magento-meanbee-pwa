# Meanbee_PWA

Add [Progressive Web App](https://developers.google.com/web/progressive-web-apps/) features to your Magento store.

Features included:
* A customisable Service Worker
* Caching page assets for faster page loads
* Offline caching catalog and CMS for viewing previously visited pages when in poor network conditions

## Installation

    modman clone git@github.com:meanbee/magento-meanbee-pwa.git
    modman deploy magento-meanbee-pwa

## Usage

Most available features are turned on automatically and require no configuration to work. However, features can be configured and customised in *System > Configuration > General > Web > Progressive Web App Settings*.

## Development

### Setting up a development environment

To setup the development environment for the module using Docker, run the following:

    docker-compose up -d db # Allow a couple of seconds for MySQL to setup itself
    docker-compose run --rm tools bash /src/setup.sh
    docker-compose up -d

### Testing service workers on Chrome

Chrome is very strict about security and only allows service workers on localhost, or on an HTTPS site with a valid certificate. To bypass these restrictions for testing, use the `--ignore-certificate-errors` and `--unsafely-treat-insecure-origin-as-secure` flags to run a less secure copy of Chrome:

    /Applications/Google\ Chrome.app/Contents/MacOS/Google\ Chrome \
        --user-data-dir=/tmp/chrome \
        --ignore-certificate-errors \
        --unsafely-treat-insecure-origin-as-secure=https://pwa-magento.docker
