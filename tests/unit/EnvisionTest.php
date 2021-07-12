<?php

declare(strict_types=1);

namespace Envision;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class EnvisionTest extends TestCase
{
    public function testGetRawFromEnvWith(): void
    {
        Envision::$options |= Envision::USE_ENV_ARRAY;

        $value = 'bar';

        $_ENV['FOO'] = $value;

        self::assertEquals($value, Envision::getRaw('FOO'));
    }

    public function testGetRawFromServer(): void
    {
        Envision::$options |= Envision::USE_SERVER_ARRAY;

        $value = 'bar';

        $_SERVER['SSS'] = $value;

        self::assertEquals($value, Envision::getRaw('SSS'));
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

    public function testGetBoolWithInvalidValue(): void
    {
        putenv('BARBOOL=inv');

        self::assertTrue(Envision::getBool('BARBOOL', true));
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

    public function testGetIntWithDefaultValue(): void
    {
        Envision::$options = Envision::ON_EMPTY_RETURN_DEFAULT;

        self::assertEquals(3, Envision::getInt('BOO', 3));
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

    public function testGetFloatWithInvalidValue(): void
    {
        putenv('FOO=sama');

        self::assertEquals(2.0, Envision::getFloat('FOO', 2.0));
    }

    public function testGetFloatWithDefaultValue(): void
    {
        Envision::$options = Envision::ON_EMPTY_RETURN_DEFAULT;

        self::assertEquals(3.0, Envision::getFloat('BOO2', 3.0));
    }

    public function testGetString(): void
    {
        putenv('SOO=bar');

        self::assertEquals('bar', Envision::getString('SOO'));
    }

    public function testGetStringWithDefaultValue(): void
    {
        self::assertEquals('bar', Envision::getString('SOOEMPTY', 'bar'));
    }

    public function testGetArray(): void
    {
        putenv('AOO=a,b,c,');

        self::assertEquals(['a', 'b', 'c'], Envision::getArray('AOO'));
    }

    public function testGetArrayWithSlashDelim(): void
    {
        putenv('AOO2=/a/b/c');

        self::assertEquals(['a', 'b', 'c'], Envision::getArray('AOO2', '/'));
    }

    public function testGetArrayWithEmptyDelim(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Envision::getArray('AOO2', '');
    }

    public function testGetArrayWithDefaultValue(): void
    {
        self::assertEquals(['a'], Envision::getArray('AOOEMPTY', '/', ['a']));
    }
}
