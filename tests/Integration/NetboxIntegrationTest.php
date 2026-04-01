<?php

declare( strict_types = 1 );

namespace Ocolin\Netbox\Tests\Integration;

use Ocolin\Netbox\Response;
use PHPUnit\Framework\TestCase;
use Ocolin\Netbox\Netbox;
use Ocolin\EasyEnv\Env;

class NetboxIntegrationTest extends TestCase
{
    public static Netbox $netbox;

    public static string|int|null $id = null;

    public function testAuthorizationCheck() : void
    {
        $output = self::$netbox->get( endpoint: '/api/authentication-check/' );
        $this->assertIsObject( $output );
        $this->assertObjectHasProperty( 'status', $output );
        $this->assertObjectHasProperty( 'statusMessage', $output );
        $this->assertObjectHasProperty( 'headers', $output );
        $this->assertObjectHasProperty( 'body', $output );
        $this->assertIsInt( $output->status );
        $this->assertSame( 200, $output->status );
        $this->assertIsString( $output->statusMessage );
        $this->assertSame( 'OK', $output->statusMessage );
        $this->assertIsArray( $output->headers );
        $this->assertIsObject( $output->body );
    }


    public function testCreateSite() : void
    {
        $output = self::$netbox->post(
            endpoint: '/api/dcim/sites/',
            body: [
                'name' => 'PHPUnit Test Site',
                'slug' => 'PHPUnit-Test-Site',
            ]
        );
        $this->assertIsObject( $output->body );
        $this->assertObjectHasProperty( 'name', $output->body );
        $this->assertSame( 'PHPUnit Test Site', $output->body->name );
        self::$id = $output->body->id;
    }

    public function testPatchSite() : void
    {
        $output = self::$netbox->patch(
            endpoint: '/api/dcim/sites/{id}/',
            query: [ 'id' => self::$id ],
            body: [ 'name' => 'PHPUnit Test Site UPDATE' ]
        );
        $this->assertIsObject( $output->body );
        $this->assertObjectHasProperty( 'name', $output->body );
        $this->assertSame( 'PHPUnit Test Site UPDATE', $output->body->name );
    }


    public function testGetSite() : void
    {
        $output = self::$netbox->get(
            endpoint: '/api/dcim/sites/{id}/',
            query: [ 'id' => self::$id ]
        );
        $this->assertIsObject( $output->body );
        $this->assertObjectHasProperty( 'id', $output->body );
        $this->assertSame( self::$id, $output->body->id );
    }

    public function testDeleteSite() : void
    {
        $output = self::deleteSite();
        $this->assertIsObject( $output );
        $this->assertObjectHasProperty( 'status', $output );
        $this->assertSame( 204, $output->status );
    }


    public static function setUpBeforeClass(): void
    {
        Env::load( files: __DIR__ . '/../../.env' );
        self::$netbox = new Netbox();
    }

    public static function tearDownAfterClass(): void
    {
        if( self::$id !== null ) {
            self::deleteSite();
        }

        self::$id = null;
        unset( $_ENV );
    }

    public static function deleteSite() : Response
    {
        return self::$netbox->delete(
            endpoint: '/api/dcim/sites/{id}',
            query: [ 'id' => self::$id ]
        );
    }
}