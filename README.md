# This is my package filament-nestedresources

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mstfkhazaal/filament-nestedresources.svg?style=flat-square)](https://packagist.org/packages/mstfkhazaal/filament-nestedresources)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/mstfkhazaal/filament-nestedresources/run-tests?label=tests)](https://github.com/mstfkhazaal/filament-nestedresources/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/mstfkhazaal/filament-nestedresources/Check%20&%20fix%20styling?label=code%20style)](https://github.com/mstfkhazaal/filament-nestedresources/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/mstfkhazaal/filament-nestedresources.svg?style=flat-square)](https://packagist.org/packages/mstfkhazaal/filament-nestedresources)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require mstfkhazaal/filament-nestedresources
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-nestedresources-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-nestedresources-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-nestedresources-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$filament-nestedresources = new Mstfkhazaal\FilamentNestedresources();
echo $filament-nestedresources->echoPhrase('Hello, Mstfkhazaal!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [mstfkhazaal](https://github.com/mstfkhazaal)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
