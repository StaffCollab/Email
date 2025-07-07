# Email By Events For Filament

[![Latest Version on Packagist](https://img.shields.io/packagist/v/staffcollab/email.svg?style=flat-square)](https://packagist.org/packages/staffcollab/email)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/staffcollab/email/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/staffcollab/email/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/staffcollab/email/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/staffcollab/email/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/staffcollab/email.svg?style=flat-square)](https://packagist.org/packages/staffcollab/email)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require staffcollab/email
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="email-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="email-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="email-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$email = new StaffCollab\Email();
echo $email->echoPhrase('Hello, StaffCollab!');
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

- [Yakov Wiznitzer](https://github.com/StaffCollab)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
