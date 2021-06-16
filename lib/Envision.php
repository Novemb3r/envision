<?php

declare(strict_types=1);

namespace Envision;

use InvalidArgumentException;

class Envision
{

    /**
     * @var int
     */
    public const ON_EMPTY_RETURN_DEFAULT = 1;

    /**
     * @var int
     */
    public const ON_EMPTY_THROW = 2;

    /**
     * @var int
     */
    public const ON_INVALID_RETURN_DEFAULT = 4;

    /**
     * @var int
     */
    public const ON_INVALID_THROW = 8;

    /**
     * @var int
     */
    public const USE_ENV_ARRAY = 16;

    /**
     * @var int
     */
    public const USE_SERVER_ARRAY = 32;

    /**
     * @var int
     */
    public static $options = self::ON_EMPTY_RETURN_DEFAULT | self::ON_INVALID_RETURN_DEFAULT;

    /**
     * @param string $name
     * @return false|mixed|string
     */
    public static function getRaw(string $name)
    {
        if ((self::$options & self::USE_ENV_ARRAY) && array_key_exists($name, $_ENV)) {
            return $_ENV[$name];
        }

        if ((self::$options & self::USE_SERVER_ARRAY) && array_key_exists($name, $_SERVER)) {
            return $_SERVER[$name];
        }

        return getenv($name);
    }

    /**
     * @param string $name
     * @param bool $default
     * @return bool
     */
    public static function getBool(string $name, bool $default = false): bool
    {
        $env = self::getRaw($name);

        if ($env === false) {
            return self::handleEmpty($name, $default);
        }

        return (bool)filter_var($env, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @param string $name
     * @param int $default
     * @return int
     */
    public static function getInt(string $name, int $default = 0): int
    {
        $env = self::getRaw($name);

        if ($env === false) {
            return self::handleEmpty($name, $default);
        }

        $value = filter_var($env, FILTER_VALIDATE_INT);

        if ($value === false) {
            return self::handleInvalid($name, $value, $default);
        }

        return $value;
    }

    /**
     * @param string $name
     * @param float $default
     * @return float
     */
    public static function getFloat(string $name, float $default = 0.0): float
    {
        $env = self::getRaw($name);

        if ($env === false) {
            return self::handleEmpty($name, $default);
        }

        $value = filter_var($env, FILTER_VALIDATE_FLOAT);

        if ($value === false) {
            return self::handleInvalid($name, $value, $default);
        }

        return $value;
    }

    /**
     * @param string $name
     * @param string $default
     * @return string
     */
    public static function getString(string $name, string $default = ''): string
    {
        $value = self::getRaw($name);

        if ($value === false) {
            return self::handleEmpty($name, $default);
        }

        return (string)$value;
    }

    /**
     * @param string $name
     * @param string $delimiter
     * @param array $default
     * @return array
     */
    public static function getArray(string $name, string $delimiter = ',', array $default = []): array
    {
        $env = self::getRaw($name);

        if ($env === false) {
            return self::handleEmpty($name, $default);
        }

        return explode($delimiter, trim($env, $delimiter));
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    private static function handleEmpty(string $name, $default)
    {
        if (self::$options & self::ON_EMPTY_THROW) {
            throw new InvalidArgumentException(
                'Unable to find variable with name ' . $name
            );
        }

        return $default;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @param mixed $default
     * @return mixed
     */
    private static function handleInvalid(string $name, $value, $default)
    {
        if (self::$options & self::ON_INVALID_THROW) {
            throw new InvalidArgumentException(
                sprintf('Invalid value for variable %s: %s', $name, $value)
            );
        }

        return $default;
    }
}
