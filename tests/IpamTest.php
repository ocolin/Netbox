<?php

declare( strict_types = 1 );

namespace Ocolin\Netbox\Tests;

use PHPUnit\Framework\TestCase;
use Ocolin\Netbox\Client;

class IpamTest extends TestCase
{
    public static Client $api;

    public static int $id;

    public function testCreateGood() : void
    {
        $output = self::$api->call(
            path: '/ipam/ip-addresses/',
            method: 'POST',
            body: [
                'address' => '192.168.88.2/24',
                'description' => 'PHPUnit Test address',
            ]
        );
        self::$id = $output->id;
        self::assertIsObject( $output );
        self::assertObjectHasProperty( 'id', $output );
        self::assertEquals( '192.168.88.2/24', $output->address );
        //print_r( $output );
    }

    public function testGetGood() : void
    {
        $output = self::$api->call(
            path: '/ipam/ip-addresses/{id}/',
            query: [ 'id' => self::$id ],
        );
        self::assertIsObject( $output );
        self::assertObjectHasProperty( 'id', $output );
        self::assertEquals( '192.168.88.2/24', $output->address );
        //print_r( $output );
    }

    public function testPatchGood() : void
    {
        $output = self::$api->call(
              path: '/ipam/ip-addresses/{id}/',
            method: 'PATCH',
             query: [ 'id' => self::$id ],
              body: [ 'description' => 'PHPUnit updated description' ]
        );
        self::assertIsObject( $output );
        self::assertObjectHasProperty( 'id', $output );
        self::assertEquals(
            'PHPUnit updated description', $output->description
        );
        //print_r( $output );
    }

    public function testDeleteGood() : void
    {
        $output = self::$api->call(
            path: '/ipam/ip-addresses/{id}/',
            method: 'DELETE',
            query: [ 'id' => self::$id ],
        );
        self::assertNull( $output );
    }

    public static function setUpBeforeClass(): void
    {
        self::$api = new Client();
    }
}