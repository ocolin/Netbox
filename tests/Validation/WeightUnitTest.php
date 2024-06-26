<?php

declare( strict_types = 1 );

namespace Tests\Validation;

use Cruzio\lib\Netbox\Validation\WeightUnit;
use PHPUnit\Framework\TestCase;

final class WeightUnitTest extends TestCase
{
    use WeightUnit;

/* GOOD TEST
----------------------------------------------------------------------------- */

    public function testGood() : void
    {

        $result = self::validate_WeightUnit( input: 'lb' );
        self::assertIsBool( $result );
        self::assertTrue( $result );
    }


/* BAD TEST
----------------------------------------------------------------------------- */

    public function testBad() : void
    {
        $result = self::validate_WeightUnit( input: 'bad input' );
        self::assertIsString( $result );
    }
}