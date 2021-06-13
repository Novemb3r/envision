<?php

declare(strict_types=1);

namespace Envision;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class EnvisionTest extends TestCase
{
    public function testGetRawFromEnvWithDefaultOptions(): void
    {
        Envision::$options |= Envision::USE_ENV_ARRAY;

        $value = 'bar';

        $_ENV['FOO'] = $value;

        self::assertEquals($value, Envision::getRaw('FOO'));
    }

    public function testGetRawFromEnvWithoutEnvArrayUse(): void
    {
        $_ENV['FOO'] = 'bar';

        Envision::$options ^= Envision::USE_ENV_ARRAY;

        self::assertEquals(null, Envision::getRaw('FOO'));
    }

    public function testGetRawFromPutenv(): void
    {
        Envision::$options = Envision::ON_EMPTY_RETURN_DEFAULT | Envision::USE_ENV_ARRAY;

        putenv('FOO=bar');

        self::assertEquals('bar', Envision::getRaw('FOO'));
    }

    public function testGetBool(): void
    {
        putenv('BAR=true');

        self::assertTrue(Envision::getBool('BAR'));
    }

    public function testGetBoolWithDefault(): void
    {
        Envision::$options = Envision::ON_EMPTY_RETURN_DEFAULT;

        self::assertTrue(Envision::getBool('WOW', true));
    }

    public function testGetBoolWithDefaultButNullReturnOption(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Envision::$options = Envision::ON_EMPTY_THROW;

        Envision::getBool('WOW', true);
    }

    public function testGetInt(): void
    {
        putenv('FOO=10');

        self::assertEquals(10, Envision::getInt('FOO'));
    }

    public function testGetIntWithInvalidValue(): void
    {
        putenv('FOO=asd');

        self::assertEquals(10, Envision::getInt('FOO', 10));
    }

    public function testGetIntWithInvalidValueAndThrowOnIvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Envision::$options = Envision::ON_EMPTY_RETURN_DEFAULT | Envision::ON_INVALID_THROW;

        putenv('FOO=asd');

        Envision::getInt('FOO', 10);
    }

    public function testGetFloat(): void
    {
        Envision::$options = Envision::ON_EMPTY_RETURN_DEFAULT | Envision::ON_INVALID_RETURN_DEFAULT;

        putenv('FOO=3.14');

        self::assertEquals(3.14, Envision::getFloat('FOO'));
    }

    public function testGetFloatWithIntValue(): void
    {
        Envision::$options = Envision::ON_EMPTY_RETURN_DEFAULT | Envision::ON_INVALID_RETURN_DEFAULT;

        putenv('FOO=10');

        self::assertEquals(10.0, Envision::getFloat('FOO'));
    }
}
