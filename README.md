ToaValidatorComponent
=====================

This Component extends [Symfony Validator Component](https://github.com/symfony/Validator) ~2.1.

[build]: https://travis-ci.org/toaotc/Validator
[coverage]: https://scrutinizer-ci.com/g/toaotc/Validator/
[quality]: https://scrutinizer-ci.com/g/toaotc/Validator/
[package]: https://packagist.org/packages/toa/validator
[dependency]: https://www.versioneye.com/user/projects/5230672d632bac1097000a25

[![Build Status](https://travis-ci.org/toaotc/Validator.png)][build]
[![Code Coverage](https://scrutinizer-ci.com/g/toaotc/Validator/badges/coverage.png?s=4f8c2449ac43420f4aa090eb54f41d7d2e228015)][coverage]
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/toaotc/Validator/badges/quality-score.png?s=07abd70c5408b2fa289be7c37c20b430a08be73e)][quality]
[![Dependency Status](https://www.versioneye.com/user/projects/5230672d632bac1097000a25/badge.png)][dependency]

[![Latest Stable Version](https://poser.pugx.org/toa/validator/v/stable.png "Latest Stable Version")][package]
[![Total Downloads](https://poser.pugx.org/toa/validator/downloads.png "Total Downloads")][package]

## Requirements ##

To use [Csv](Constraints/Csv.php) constraint you have to install [Goodby CSV](https://github.com/goodby/csv):

    {
        "require": {
            "goodby/csv": "*"
        }
    }

To use [Audio](Constraints/Audio.php) or [Video](Constraints/Video.php) constraint you have to install [PHP FFmpeg](https://github.com/alchemy-fr/PHP-FFmpeg):

    {
        "require": {
            "php-ffmpeg/php-ffmpeg": "*"
        }
    }



## Installation ##

Add this component to your `composer.json` file:

    {
        "require": {
            "toa/validator": "dev-master"
        }
    }

## Usage ##

Read the [documentation](Resources/doc/index.md).

General information to use validation constraints can be found in the [Symfony documentation](http://symfony.com/doc/current/book/validation.html).

