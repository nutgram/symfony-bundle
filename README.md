# Nutgram Bundle for Symfony
[![Total Downloads](https://img.shields.io/packagist/dt/nutgram/symfony-bundle.svg?style=flat-square)](https://packagist.org/packages/nutgram/symfony-bundle)
![GitHub](https://img.shields.io/github/license/nutgram/symfony-bundle)

[Nutgram](https://github.com/nutgram/nutgram) 5 integration for Symfony 6, 7 and 8. Requires PHP 8.4 or later.

## Installation

You can install the package via composer:

```bash
composer require nutgram/symfony-bundle
```

## Usage

When upgrading from Nutgram 4, remove `nutgram.config.botId` from
`config/packages/nutgram.yaml`. Nutgram 5 reads the bot ID from the token.

- [Official Documentation for Symfony](https://nutgram.dev/docs/configuration/symfony)

## Credits

- [Sergio Brighenti](https://github.com/SergiX44)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

