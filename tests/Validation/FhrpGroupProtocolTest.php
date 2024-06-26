<?php

declare( strict_types = 1 );

namespace Tests\Validation;

use Cruzio\lib\Netbox\Validation\FhrpGroupProtocol;
use PHPUnit\Framework\TestCase;

final class FhrpGroupProtocolTest extends TestCase
{
    use FhrpGroupProtocol;

/* GOOD TEST
----------------------------------------------------------------------------- */

    public function testGood() : void
    {
        $result = self::validate_FhrpGroupProtocol( input: 'vrrp2' );
        self::assertIsBool( $result );
        self::assertTrue( $result );
    }


/* BAD TEST
----------------------------------------------------------------------------- */

    public function testBad() : void
    {
        $result = self::validate_FhrpGroupProtocol( input: 'bad input' );
        self::assertIsString( $result );
    }
}