# PHP SVG Font

<p align="center">
<a href="https://github.com/cybercog/php-svg-font/releases"><img src="https://img.shields.io/github/release/cybercog/php-svg-font.svg?style=flat-square" alt="Releases"></a>
<a href="https://github.com/cybercog/php-svg-font/actions/workflows/tests.yml"><img src="https://img.shields.io/github/actions/workflow/status/cybercog/php-svg-font/tests.yml?style=flat-square" alt="Build"></a>
<a href="https://github.com/cybercog/php-svg-font/blob/master/LICENSE"><img src="https://img.shields.io/github/license/cybercog/php-svg-font.svg?style=flat-square" alt="License"></a>
</p>

## Introduction

PHP SVG Font files reader and manipulator. It uses [PHP Unicode](https://github.com/cybercog/php-unicode) package under the hood.

## Installation

Pull in the package through Composer.

```shell
composer require cybercog/php-svg-font
```

## Usage

### Instantiate FontList object

```php
$fontList = \Cog\SvgFont\FontList::ofFile(__DIR__ . '/DejaVuSans.svg');
```

### Retrieve SvgFont object from the FontList

```php
$font = \Cog\SvgFont\FontList::ofFile(__DIR__ . '/DejaVuSans.svg')->getById('DejaVuSans');
```

## License

- `PHP SVG Font` package is open-sourced software licensed under the [MIT license](LICENSE) by [Anton Komarev].

## About CyberCog

[CyberCog] is a Social Unity of enthusiasts. Research the best solutions in product & software development is our passion.

- [Follow us on Twitter](https://twitter.com/cybercog)

<a href="https://cybercog.su"><img src="https://cloud.githubusercontent.com/assets/1849174/18418932/e9edb390-7860-11e6-8a43-aa3fad524664.png" alt="CyberCog"></a>

[Anton Komarev]: https://komarev.com
[CyberCog]: https://cybercog.su
