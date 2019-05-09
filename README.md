ZF3 Restuarants Finder Application
=================

Description
==================

Find Restaurants web-application base on ZF3 Framework & VueJS

## Installation

The suggested installation method is via [composer](https://getcomposer.org/):


## Configure module
* Copy `/module/RestuarantsGuide/config/restuarants-api.global.php.dist` into your global autoload folder, remove the dist extension so that Zend Framework picks it up
* Replace the `google-place-api-key'` with your google place api key

### Third Party Packages 
```tmarois/google-places-api```
This package allows you to connect and pull information from the [Google Places API](https://developers.google.com/places/web-service/intro)


```phpfastcache/phpfastcache```
Provide cache layer for this project we set cache driver into FILE system and set CLT into 5 minutes


