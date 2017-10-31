# Deezer API PHP

[![Build Status](https://travis-ci.org/PouleR/deezer-api.svg?branch=master)](https://travis-ci.org/PouleR/deezer-api)

This is a PHP wrapper for the [Deezer API](https://developers.deezer.com/api/).

## Requirements
* PHP 7.0 or later.
* HTTP Client

### HTTP Clients
This wrapper relies on HTTPlug, which defines how HTTP message should be sent and received. You can use any library to send HTTP messages
that implements [php-http/client-implementation](https://packagist.org/providers/php-http/client-implementation).

Here is a list of all officially supported clients and adapters by HTTPlug: http://docs.php-http.org/en/latest/clients.html

Read more about HTTPlug in [their docs](http://docs.php-http.org/en/latest/httplug/users.html).

## Installation
Install it using [Composer](https://getcomposer.org/):

```sh
composer require pouler/deezer-api
```

To install with Guzzle 6 you may run the following command: 

```
$ composer require pouler/deezer-api php-http/guzzle6-adapter php-http/message
```

## Usage
Before using the Deezer API, you'll need to create an app at [Deezer's developer site](https://developers.deezer.com/api/).
After you've obtained an access token you can retrieve information from a user.

```php
require 'vendor/autoload.php';

$client = new PouleR\DeezerAPI\DeezerAPIClient();
$client->setAccessToken('');

$api = new PouleR\DeezerAPI\DeezerAPI($client);

print_r($api->getUserInformation());
}
```
