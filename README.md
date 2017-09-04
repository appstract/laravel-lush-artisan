# Artisan Commands For Lush Http

[![Latest Version on Packagist](https://img.shields.io/packagist/v/appstract/laravel-lush-artisan.svg?style=flat-square)](https://packagist.org/packages/appstract/laravel-opcache)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/appstract/laravel-lush-artisan.svg?style=flat-square)](https://packagist.org/packages/appstract/laravel-opcache)

This package contains some useful Artisan commands, to work with and debug [Lush Http](https://github.com/appstract/lush-http).

<img src="screen.png?raw=true">

## Installation

You can install the package via Composer:

``` bash
composer require appstract/laravel-lush-artisan
```

For Laravel older than 5.5, register the service provider to your `config/app.php` file:

```php
'providers' => [
    ...
    Appstract\LushArtisan\LushArtisanServiceProvider::class,
];
```

## Usage

Make a GET request:
``` bash
php artisan lush:get <url>
```

Make a HEAD request:
``` bash
php artisan lush:head <url>
```

Listen for Lush requests in your application and show useful debug info:
``` bash
php artisan lush:watch
```

## Contributing

Contributions are welcome, [thanks to y'all](https://github.com/appstract/laravel-lush-artisan/graphs/contributors) :)

## About Appstract

Appstract is a small team from The Netherlands. We create (open source) tools for webdevelopment and write about related subjects on [Medium](https://medium.com/appstract). You can [follow us on Twitter](https://twitter.com/teamappstract), [buy us a beer](https://www.paypal.me/teamappstract/10) or [support us on Patreon](https://www.patreon.com/appstract).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
