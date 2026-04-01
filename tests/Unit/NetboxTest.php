<?php

declare( strict_types = 1 );

namespace Ocolin\Netbox\Tests\Unit;

use Ocolin\Netbox\Config;
use Ocolin\Netbox\HTTP;
use Ocolin\Netbox\Netbox;
use Ocolin\Netbox\Response;
use PHPUnit\Framework\TestCase;

class NetboxTest extends TestCase
{

/* HELPERS
----------------------------------------------------------------------------- */

    private function makeConfig() : Config
    {
        return new Config(
            host:  'https://netbox.example.com',
            auth:  2,
            token: 'mytoken',
            key:   'mykey',
        );
    }

    private function makeResponse( int $status = 200 ) : Response
    {
        return new Response(
            status:        $status,
            statusMessage: 'OK',
            headers:       [],
            body:          []
        );
    }

    private function makeHTTP( string $expectedMethod, Response $response ) : HTTP
    {
        $http = $this->createMock( HTTP::class );
        $http->expects( $this->once() )
            ->method( 'request' )
            ->with(
                $this->anything(),
                $this->equalTo( $expectedMethod ),
            )
            ->willReturn( $response );

        return $http;
    }



/* CONSTRUCTOR USES ENV VARS WHEN NO CONFIG PROVIDED
----------------------------------------------------------------------------- */

    public function test_constructor_uses_env_vars_when_no_config_provided() : void
    {
        $_ENV['NETBOX_API_HOST'] = 'https://netbox.example.com';
        $_ENV['NETBOX_API_TOKEN'] = 'test';
        $_ENV['NETBOX_API_KEY'] = 'test';
        $http   = $this->createStub( HTTP::class );
        $netbox = new Netbox( http: $http );

        $this->assertInstanceOf( expected: Netbox::class, actual: $netbox );
    }



/* GET CALLS HTTP WITH GET METHOD
----------------------------------------------------------------------------- */

    public function test_get_calls_http_with_get_method() : void
    {
        $response = $this->makeResponse();
        $http     = $this->makeHTTP( expectedMethod: 'GET', response: $response );
        $netbox   = new Netbox( config: $this->makeConfig(), http: $http );

        $result = $netbox->get( endpoint: '/api/dcim/devices/' );

        $this->assertSame( expected: $response, actual: $result );
    }



/* POST CALLS HTTP WITH POST METHOD
----------------------------------------------------------------------------- */

    public function test_post_calls_http_with_post_method() : void
    {
        $response = $this->makeResponse( status: 201 );
        $http     = $this->makeHTTP( expectedMethod: 'POST', response: $response );
        $netbox   = new Netbox( config: $this->makeConfig(), http: $http );

        $result = $netbox->post(
            endpoint: '/api/dcim/devices/',
            body:     [ 'name' => 'Test' ]
        );

        $this->assertSame( expected: $response, actual: $result );
    }



/* PUT CALLS HTTP WITH PUT METHOD
----------------------------------------------------------------------------- */

    public function test_put_calls_http_with_put_method() : void
    {
        $response = $this->makeResponse();
        $http     = $this->makeHTTP( expectedMethod: 'PUT', response: $response );
        $netbox   = new Netbox( config: $this->makeConfig(), http: $http );

        $result = $netbox->put(
            endpoint: '/api/dcim/devices/{id}/',
            query:    [ 'id' => 1 ],
            body:     [ 'name' => 'Test' ]
        );

        $this->assertSame( expected: $response, actual: $result );
    }



/* PATCH CALLS HTTP WITH PATCH METHOD
----------------------------------------------------------------------------- */

    public function test_patch_calls_http_with_patch_method() : void
    {
        $response = $this->makeResponse();
        $http     = $this->makeHTTP( expectedMethod: 'PATCH', response: $response );
        $netbox   = new Netbox( config: $this->makeConfig(), http: $http );

        $result = $netbox->patch(
            endpoint: '/api/dcim/devices/{id}/',
            query:    [ 'id' => 1 ],
            body:     [ 'name' => 'Test' ]
        );

        $this->assertSame( expected: $response, actual: $result );
    }



/* DELETE CALLS HTTP WITH DELETE METHOD
----------------------------------------------------------------------------- */

    public function test_delete_calls_http_with_delete_method() : void
    {
        $response = $this->makeResponse( status: 204 );
        $http     = $this->makeHTTP( expectedMethod: 'DELETE', response: $response );
        $netbox   = new Netbox( config: $this->makeConfig(), http: $http );

        $result = $netbox->delete(
            endpoint: '/api/dcim/devices/{id}/',
            query:    [ 'id' => 1 ]
        );

        $this->assertSame( expected: $response, actual: $result );
    }



/* OBJECT QUERY IS CAST TO ARRAY
----------------------------------------------------------------------------- */

    public function test_object_query_is_cast_to_array() : void
    {
        $http = $this->createMock( HTTP::class );
        $http->expects( $this->once() )
            ->method( 'request' )
            ->with(
                $this->anything(),
                $this->anything(),
                $this->callback( fn( $query ) => is_array( $query ) ),
            )
            ->willReturn( $this->makeResponse() );

        $netbox = new Netbox( config: $this->makeConfig(), http: $http );
        $query  = new \stdClass();
        $query->limit = 50;

        $netbox->get( endpoint: '/api/dcim/devices/', query: $query );
    }



/* OBJECT BODY IS CAST TO ARRAY
----------------------------------------------------------------------------- */

    public function test_object_body_is_cast_to_array() : void
    {
        $http = $this->createMock( HTTP::class );
        $http->expects( $this->once() )
            ->method( 'request' )
            ->with(
                $this->anything(),
                $this->anything(),
                $this->anything(),
                $this->callback( fn( $body ) => is_array( $body ) ),
            )
            ->willReturn( $this->makeResponse( status: 201 ) );

        $netbox = new Netbox( config: $this->makeConfig(), http: $http );
        $body   = new \stdClass();
        $body->name = 'Test Device';

        $netbox->post( endpoint: '/api/dcim/devices/', body: $body );
    }


/* OPTIONS TEST
----------------------------------------------------------------------------- */

    public function test_options_calls_http_with_options_method() : void
    {
        $response = $this->makeResponse();
        $http     = $this->makeHTTP( expectedMethod: 'OPTIONS', response: $response );
        $netbox   = new Netbox( config: $this->makeConfig(), http: $http );

        $result = $netbox->options( endpoint: '/api/dcim/devices/' );

        $this->assertSame( expected: $response, actual: $result );
    }
}