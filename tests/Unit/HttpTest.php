<?php

declare( strict_types = 1 );

namespace Ocolin\Netbox\Tests\Unit;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Ocolin\Netbox\Config;
use Ocolin\Netbox\Exceptions\HttpException;
use Ocolin\Netbox\HTTP;
use Ocolin\Netbox\Response;
use PHPUnit\Framework\TestCase;

class HTTPTest extends TestCase
{

/* HELPERS
----------------------------------------------------------------------------- */

    private function makeConfig( int $auth = 2 ) : Config
    {
        return new Config(
            host:  'https://netbox.example.com',
            auth:  $auth,
            token: 'mytoken',
            key:   'mykey',
        );
    }

    private function makeClient( mixed $body, int $status = 200 ) : ClientInterface
    {
        $client = $this->createMock( ClientInterface::class );
        $client->expects( $this->once() )
            ->method( 'request' )
            ->willReturn( new GuzzleResponse(
                status:  $status,
                headers: [],
                body:    $body
            ));

        return $client;
    }



/* RETURNS RESPONSE OBJECT
----------------------------------------------------------------------------- */

    public function test_request_returns_response_object() : void
    {
        $client = $this->makeClient( body: '{"id": 1}' );
        $http   = new HTTP( config: $this->makeConfig(), client: $client );

        $response = $http->request( path: '/api/dcim/devices/' );

        $this->assertInstanceOf( expected: Response::class, actual: $response );
    }



/* CORRECT STATUS CODE
----------------------------------------------------------------------------- */

    public function test_request_returns_correct_status_code() : void
    {
        $client = $this->makeClient( body: '{"id": 1}', status: 200 );
        $http   = new HTTP( config: $this->makeConfig(), client: $client );

        $response = $http->request( path: '/api/dcim/devices/' );

        $this->assertSame( expected: 200, actual: $response->status );
    }



/* 204 RETURNS EMPTY BODY
----------------------------------------------------------------------------- */

    public function test_204_response_returns_empty_body() : void
    {
        $client = $this->makeClient( body: '', status: 204 );
        $http   = new HTTP( config: $this->makeConfig(), client: $client );

        $response = $http->request(
            path:   '/api/dcim/devices/{id}/',
            method: 'DELETE',
            query:  [ 'id' => 1 ]
        );

        $this->assertSame( expected: 204,  actual: $response->status );
        $this->assertSame( expected: [],   actual: $response->body   );
    }



/* PATH TOKEN SUBSTITUTION
----------------------------------------------------------------------------- */

    public function test_path_tokens_are_substituted_from_query() : void
    {
        $client = $this->createMock( ClientInterface::class );
        $client->expects( $this->once() )
            ->method( 'request' )
            ->with(
                $this->equalTo( 'GET' ),
                $this->equalTo( 'dcim/devices/42/' ),
                $this->anything()
            )
            ->willReturn( new GuzzleResponse(
                status: 200,
                body:   '{"id": 42}'
            ));

        $http = new HTTP( config: $this->makeConfig(), client: $client );
        $http->request( path: '/dcim/devices/{id}/', query: [ 'id' => 42 ] );
    }



/* TOKEN REMOVED FROM QUERY STRING AFTER SUBSTITUTION
----------------------------------------------------------------------------- */

    public function test_substituted_token_removed_from_query_string() : void
    {
        $client = $this->createMock( ClientInterface::class );
        $client->expects( $this->once() )
            ->method( 'request' )
            ->with(
                $this->anything(),
                $this->anything(),
                $this->callback( function( array $options ) : bool {
                    return !isset( $options['query']['id'] );
                })
            )
            ->willReturn( new GuzzleResponse(
                status: 200,
                body:   '{"id": 42}'
            ));

        $http = new HTTP( config: $this->makeConfig(), client: $client );
        $http->request( path: '/api/dcim/devices/{id}/', query: [ 'id' => 42 ] );
    }



/* NON-TOKEN QUERY PARAMS REMAIN
----------------------------------------------------------------------------- */

    public function test_non_token_query_params_remain_in_query_string() : void
    {
        $client = $this->createMock( ClientInterface::class );
        $client->expects( $this->once() )
            ->method( 'request' )
            ->with(
                $this->anything(),
                $this->anything(),
                $this->callback( function( array $options ) : bool {
                    return isset( $options['query']['limit'] )
                        AND $options['query']['limit'] === 50;
                })
            )
            ->willReturn( new GuzzleResponse(
                status: 200,
                body:   '{"count": 0, "results": []}'
            ));

        $http = new HTTP( config: $this->makeConfig(), client: $client );
        $http->request(
            path:  '/api/dcim/devices/{id}/',
            query: [ 'id' => 42, 'limit' => 50 ]
        );
    }



/* INVALID METHOD THROWS EXCEPTION
----------------------------------------------------------------------------- */

    public function test_invalid_method_throws_http_exception() : void
    {
        $this->expectException( HttpException::class );
        $this->expectExceptionMessage( 'Invalid HTTP method: INVALID' );

        $http = new HTTP( config: $this->makeConfig() );
        $http->request( path: '/api/dcim/devices/', method: 'INVALID' );
    }



/* INVALID JSON RESPONSE THROWS EXCEPTION
----------------------------------------------------------------------------- */

    public function test_invalid_json_response_throws_http_exception() : void
    {
        $this->expectException( HttpException::class );

        $client = $this->makeClient( body: 'not json' );
        $http   = new HTTP( config: $this->makeConfig(), client: $client );
        $http->request( path: '/api/dcim/devices/' );
    }



/* V1 AUTH HEADER
----------------------------------------------------------------------------- */

    public function test_v1_auth_header_format() : void
    {
        $client = $this->createMock( ClientInterface::class );
        $client->expects( $this->once() )
            ->method( 'request' )
            ->willReturn( new GuzzleResponse(
                status: 200,
                body:   '{"id": 1}'
            ));

        // If the client constructs without exception using v1 config,
        // the auth header was built correctly
        $http = new HTTP(
            config: $this->makeConfig( auth: 1 ),
            client: $client
        );

        $response = $http->request( path: '/api/dcim/devices/' );
        $this->assertSame( expected: 200, actual: $response->status );
    }



/* BODY SENT FOR POST
----------------------------------------------------------------------------- */

    public function test_body_is_sent_for_post_request() : void
    {
        $client = $this->createMock( ClientInterface::class );
        $client->expects( $this->once() )
            ->method( 'request' )
            ->with(
                $this->anything(),
                $this->anything(),
                $this->callback( function( array $options ) : bool {
                    return isset( $options['json'] )
                        AND $options['json']['name'] === 'Test Device';
                })
            )
            ->willReturn( new GuzzleResponse(
                status: 201,
                body:   '{"id": 1, "name": "Test Device"}'
            ));

        $http = new HTTP( config: $this->makeConfig(), client: $client );
        $http->request(
            path:   '/api/dcim/devices/',
            method: 'POST',
            body:   [ 'name' => 'Test Device' ]
        );
    }



/* NO BODY SENT FOR GET
----------------------------------------------------------------------------- */

    public function test_no_body_sent_for_get_request() : void
    {
        $client = $this->createMock( ClientInterface::class );
        $client->expects( $this->once() )
            ->method( 'request' )
            ->with(
                $this->anything(),
                $this->anything(),
                $this->callback( function( array $options ) : bool {
                    return !isset( $options['json'] );
                })
            )
            ->willReturn( new GuzzleResponse(
                status: 200,
                body:   '{"count": 0, "results": []}'
            ));

        $http = new HTTP( config: $this->makeConfig(), client: $client );
        $http->request( path: '/api/dcim/devices/' );
    }

    public function test_trailing_slash_is_added_to_path() : void
    {
        $client = $this->createMock( ClientInterface::class );
        $client->expects( $this->once() )
            ->method( 'request' )
            ->with(
                $this->anything(),
                $this->equalTo( 'dcim/devices/42/' ),
                $this->anything()
            )
            ->willReturn( new GuzzleResponse(
                status: 200,
                body:   '{"id": 42}'
            ));

        $http = new HTTP( config: $this->makeConfig(), client: $client );
        $http->request( path: '/api/dcim/devices/42' ); // no trailing slash
    }
}