<?php

declare( strict_types = 1 );

namespace Tests\Validation;

use Cruzio\lib\Netbox\Validation\VlanStatus;
use PHPUnit\Framework\TestCase;

final class VlanStatusTest extends TestCase
{
    use VlanStatus;

/* GOOD TEST
----------------------------------------------------------------------------- */

    public function testGood() : void
    {
        $result = self::validate_VlanStatus( input: 'active' );
        self::assertIsBool( $result );
        self::assertTrue( $result );
    }


/* BAD TEST
----------------------------------------------------------------------------- */

    public function testBad() : void
    {
        $result = self::validate_VlanStatus( input: 'bad input' );
        self::assertIsString( $result );
    }
}