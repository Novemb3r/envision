# Envision

[![packagist-dt-badge]][packagist] ![php-version-badge] [![travis-build-status]][travis-link] [![codecov]][codecov-link] ![license]

Simplest library to bring types for your env variables!

## Installing

This library installs as a [composer package](https://packagist.org/packages/novemb3r/envision) with

```
$ composer require novemb3r/envision
```

or

```JSON
{
    "require": {
        "novemb3r/envision": "dev-master"
    }
}
```

## Usage
### Examples

Get boolean variable (default is false):

```PHP
Envision::getBool('FOO');
```

or with `true` as default:

```PHP
Envision::getBool('FOO', true);
```

Get an integer or float:

```PHP
Envision::getInt('FOO');
Envision::getFloat('FOO', 3.141592);
```

Get string:

```PHP
Envision::getString('FOO');
Envision::getString('FOO', 'default');
```

Get an array specified like

```
FOO=v1,v2,v3
FOO2=v1,v2,v3,
FOO3=v1/v2/v3
```

by passing delimiter (default is `,`)

```PHP

Envision::getArray('FOO');
Envision::getArray('FOO1', ',');
Envision::getArray('FOO3', ',', ['v2']);
```

### Configuration

Envision has a set of options to control it's behaviour. Default behaviour is not to look for env variables in `$_ENV`
and `$_SERVER` arrays. It's okay if you are using symfony/dotenv component to load .env files.
But you can change this using

```PHP
Envision::$options = Envision::USE_ENV_ARRAY | Envision::USE_SERVER_ARRAY;
```

Also, you can configure Envision to throw `InvalidArgumentException` if env variable cannot be found:

```PHP
Envision::$options = Envision::ON_EMPTY_THROW;
```

or if variable has invalid format:

```PHP
Envision::$options = Envision::ON_INVALID_THROW;

```

by default Envision will return default values in both cases, which equals to

```PHP
Envision::$options = Envision::ON_EMPTY_RETURN_DEFAULT | Envision::ON_INVALID_RETURN_DEFAULT;
```

## Testing

To execute test suites run

```shell
$ composer test
```

always run 
```shell
$ composer prepare-push
```
before making any changes

<!-- Badges -->

[packagist-dt-badge]: https://img.shields.io/packagist/dt/novemb3r/envision.svg?style=flat-square

[packagist]: https://packagist.org/packages/novemb3r/envision

[php-version-badge]: https://img.shields.io/packagist/php-v/suin/json.svg?style=flat-square

[license]: https://img.shields.io/badge/License-MIT-green.svg?style=flat-square

[travis-build-status]: https://img.shields.io/travis/Novemb3r/envision?style=flat-square
[travis-link]: https://travis-ci.com/Novemb3r/envision

[codecov]: https://img.shields.io/codecov/c/github/Novemb3r/envision?style=flat-square&token=EZNYXY93EZ

[codecov-link]: https://codecov.io/gh/Novemb3r/envision