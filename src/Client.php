<?php

declare( strict_types = 1 );

namespace Ocolin\Netbox;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class Client
{


    public HTTP $http;


/* CONSTRUCTOR
----------------------------------------------------------------------------- */

    public function __construct(
        ?string $host    = null,
        ?string $token   = null,
            int $timeout = 20,
           bool $verify  = false,
    ) {

        $this->http = new HTTP(
                url: $host,
              token: $token,
            timeout: $timeout,
             verify: $verify
        );
    }



/* API RESPONSE BODY ONLY
----------------------------------------------------------------------------- */

    /**
     * @param string $path API end point path.
     * @param string $method API HTTP method to use.
     * @param array<string, string|int|float>|object|null $query Path and Query parameters.
     * @param array<string, mixed>|object|null $body Body parameters for POST/PUT.
     * @return mixed Output of API service response.
     * @throws GuzzleException
     */
    public function call(
        string $path,
        string $method = 'GET',
        array|object|null $query = null,
        array|object|null $body = null,
    ) : mixed
    {
        $output = $this->full(
              path: $path,
            method: $method,
             query: $query,
              body: $body,
        );

        return $output->body;
    }



/* API FULL CALL
----------------------------------------------------------------------------- */

    /**
     * @param string $path
     * @param string $method
     * @param array<string, string|int|float>|object|null $query
     * @param array<string, mixed>|object|null $body
     * @return Response
     * @throws GuzzleException
     */
    public function full(
        string $path,
        string $method = 'GET',
        array|object|null $query = null,
        array|object|null $body = null,
    ) : Response
    {
        $method = strtoupper( string: $method );

        return match( $method )
        {
            // CREATE OBJECT
            'POST' => self::format_Response(
                 response: $this->http->post(
                     path: $path,
                    query: $query,
                     body: $body,
                )
            ),
            // UPDATE ENTIRE OBJECT
            'PUT' => self::format_Response(
                 response: $this->http->put(
                     path: $path,
                    query: $query,
                     body: $body,
                )
            ),
            // UPDATE SPECIFIC FIELDS OF OBJECT
            'PATCH' => self::format_Response(
                 response: $this->http->patch(
                     path: $path,
                    query: $query,
                     body: $body,
                )
            ),
            // DELETE OBJECT
            'DELETE' => self::format_Response(
                 response: $this->http->delete(
                     path: $path,
                    query: $query,
                )
            ),
            // GET OBJECTS
            default => self::format_Response(
                 response: $this->http->get(
                     path: $path,
                    query: $query,
                )
            ),
        };
    }



/* FORMAT HTTP RESPONSE
----------------------------------------------------------------------------- */

    /**
     * @param ResponseInterface $response Guzzle response object.
     * @return Response Formatted response object.
     */
    private static function format_Response( ResponseInterface $response ) : Response
    {
        return new Response(
                   status: $response->getStatusCode(),
            statusMessage: $response->getReasonPhrase(),
                  headers: $response->getHeaders(),
                     body: json_decode( json: $response->getBody()->getContents())
        );
    }
}