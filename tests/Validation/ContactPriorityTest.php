<?php

declare( strict_types = 1 );

namespace Tests\Validation;

use Cruzio\lib\Netbox\Validation\ContactPriority;
use PHPUnit\Framework\TestCase;

final class ContactPriorityTest extends TestCase
{
    use ContactPriority;

/* GOOD TEST
----------------------------------------------------------------------------- */

    public function testGood() : void
    {
        $result = self::validate_ContactPriority( input: 'secondary' );
        self::assertIsBool( $result );
        self::assertTrue( $result );
    }

/* BAD TEST
----------------------------------------------------------------------------- */

    public function testBad() : void
    {
        $result = self::validate_ContactPriority( input: 'bad input' );
        self::assertIsString( $result );
    }
}