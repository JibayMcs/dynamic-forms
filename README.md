# A filament plugin to create dynamic Form Schema from json representation

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jibaymcs/dynamic-forms.svg?style=flat-square)](https://packagist.org/packages/jibaymcs/dynamic-forms)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jibaymcs/dynamic-forms/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jibaymcs/dynamic-forms/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jibaymcs/dynamic-forms/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/jibaymcs/dynamic-forms/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jibaymcs/dynamic-forms.svg?style=flat-square)](https://packagist.org/packages/jibaymcs/dynamic-forms)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require jibaymcs/dynamic-forms
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="dynamic-forms-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="dynamic-forms-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="dynamic-forms-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$dynamicForms = new JibayMcs\DynamicForms();
echo $dynamicForms->echoPhrase('Hello, JibayMcs!');
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

- [Jean-Baptiste Macias](https://github.com/JibayMcs)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
