# Graylog for magento 2 

## By me a coffee:
- https://patreon.com/solutiontutorials
- https://paypal.me/hidrole 

Release version pattern:
- Version pattern: [Magento Version].x.x
- Ex: Version format 2.4.x.x (**This pattern for Magento 2.4**).


    hidro/module-graylog
    
 Packagist: https://packagist.org/packages/hidro/module-graylog

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)


# More packages: 
- Core webvital: https://github.com/hieuhidro/core-web-vitals
- Checkout my paid version of Magento 2 CWV module: https://store.solutiontutorials.com/magento-2-critical-css.html


## Main Functionalities


## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/Hidro`
 - Enable the module by running `php bin/magento module:enable Hidro_Graylog`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Make the module available in a composer repository for example:
    - public repository `packagist.org`
    - public github repository as vcs
 - Install the module composer by running `composer require hidro/module-graylog`
 - enable the module by running `php bin/magento module:enable Hidro_Graylog`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`


## Configuration

 - Enabled (system/graylog/enabled)

 - Host (system/graylog/host)

 - Port (system/graylog/port)


## Specifications

 - Console Command
	- test

## Attributes

## My Website
 - https://www.solutiontutorials.com/
