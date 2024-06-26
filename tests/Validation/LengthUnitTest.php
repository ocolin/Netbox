<?php

declare( strict_types = 1 );

namespace Tests\Validation;

use Cruzio\lib\Netbox\Validation\LengthUnit;
use PHPUnit\Framework\TestCase;

final class LengthUnitTest extends TestCase
{
    use LengthUnit;

/* GOOD TEST
----------------------------------------------------------------------------- */

    public function testGood() : void
    {
        $result = self::validate_LengthUnit( input: 'km' );
        self::assertIsBool( $result );
        self::assertTrue( $result );
    }


/* BAD TEST
----------------------------------------------------------------------------- */

    public function testBad() : void
    {
        $result = self::validate_LengthUnit( input: 'bad input' );
        self::assertIsString( $result );
    }
}