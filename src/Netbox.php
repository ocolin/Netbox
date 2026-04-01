<?php

declare( strict_types = 1 );

namespace Ocolin\Netbox;

use Ocolin\Netbox\Exceptions\HttpException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

class Netbox
{
    /**
     * @var HTTP HTTP engine.
     */
    private HTTP $http;


/* CONSTRUCTOR
----------------------------------------------------------------------------- */

    /**
     * @param Config|null $config Configuration data object.
     * @param ?HTTP $http HTTP client for mocking.
     * @param ClientInterface|null $client Guzzle client for mocking.
     */
    public function __construct(
                 ?Config $config = null,
                   ?HTTP $http   = null,
        ?ClientInterface $client = null
    )
    {
        $config = $config ?? new Config();

        $this->http = $http ?? new HTTP( config: $config, client: $client );
    }



/* GET METHOD
----------------------------------------------------------------------------- */

    /**
     * @param string $endpoint URI endpoint.
     * @param array<string, string|int|float|bool>|object $query HTTP query.
     * @return Response API client response object/
     * @throws GuzzleException
     * @throws HttpException
     */
    public function get( string $endpoint, array|object $query = [] ) : Response
    {
        $query = (array)$query;
        return $this->http->request( path: $endpoint, query: $query );
    }



/* POST METHOD
----------------------------------------------------------------------------- */

    /**
     * @param string $endpoint API URI endpoint.
     * @param array<string, string|int|float|bool>|object $query HTTP query.
     * @param array<string, mixed>|object $body HTTP POST body.
     * @return Response API client response object.
     * @throws HttpException
     * @throws GuzzleException
     */
    public function post(
              string $endpoint,
        array|object $query = [],
        array|object $body = []
    ) : Response
    {
        $query = (array)$query;
        $body  = (array)$body;
        return $this->http->request(
              path: $endpoint,
            method: 'POST',
             query: $query,
              body: $body
        );
    }



/* PUT METHOD
----------------------------------------------------------------------------- */

    /**
     * @param string $endpoint API URI endpoint.
     * @param array<string, string|int|float|bool>|object $query HTTP query.
     * @param array<string, mixed>|object $body HTTP POST body.
     * @return Response API client response object.
     * @throws GuzzleException
     * @throws HttpException
     */
    public function put(
              string $endpoint,
        array|object $query = [],
        array|object $body = []
    ) : Response
    {
        $query = (array)$query;
        $body = (array)$body;
        return $this->http->request(
              path: $endpoint,
            method: 'PUT',
             query: $query,
              body: $body
        );
    }



/* PATCH METHOD
----------------------------------------------------------------------------- */

    /**
     * @param string $endpoint API URI endpoint.
     * @param array<string, string|int|float|bool>|object $query HTTP query.
     * @param array<string, mixed>|object $body HTTP POST body.
     * @return Response API client response object.
     * @throws GuzzleException
     * @throws HttpException
     */
    public function patch(
              string $endpoint,
        array|object $query = [],
        array|object $body = []
    ) : Response
    {
        $query = (array)$query;
        $body = (array)$body;
        return $this->http->request(
              path: $endpoint,
            method: 'PATCH',
             query: $query,
              body: $body
        );
    }



/* DELETE METHOD
----------------------------------------------------------------------------- */

    /**
     * @param string $endpoint API URI endpoint.
     * @param array<string, string|int|float|bool>|object $query HTTP query.
     * @param array<string, mixed>|object $body HTTP POST body.
     * @return Response API client response object.
     * @throws GuzzleException
     * @throws HttpException
     */
    public function delete(
              string $endpoint,
        array|object $query = [],
        array|object $body = []
    ) : Response
    {
        $query = (array)$query;
        $body = (array)$body;
        return $this->http->request(
              path: $endpoint,
            method: 'DELETE',
             query: $query,
              body: $body
        );
    }

/* OPTIONS METHOD
----------------------------------------------------------------------------- */

    /**
     * @param string $endpoint API URI endpoint.
     * @return Response API client response object.
     * @throws GuzzleException
     * @throws HttpException
     */
    public function options( string $endpoint ) : Response
    {
        return $this->http->request( path: $endpoint, method: 'OPTIONS' );
    }
}