<?php

declare( strict_types = 1 );

namespace Tests\Validation;

use Cruzio\lib\Netbox\Validation\RfRole;
use PHPUnit\Framework\TestCase;

final class RfRoleTest extends TestCase
{
    use RfRole;

/* GOOD TEST
----------------------------------------------------------------------------- */

    public function testGood() : void
    {
        $result = self::validate_RfRole( input: 'station' );
        self::assertIsBool( $result );
        self::assertTrue( $result );
    }


/* BAD TEST
----------------------------------------------------------------------------- */

    public function testBad() : void
    {
        $result = self::validate_RfRole( input: 'bad input' );
        self::assertIsString( $result );
    }
}