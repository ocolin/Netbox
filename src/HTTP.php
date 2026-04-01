<?php

declare( strict_types = 1 );

namespace Ocolin\Netbox;

use GuzzleHttp\Exception\GuzzleException;
use Ocolin\Netbox\Exceptions\HttpException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client AS GuzzleClient;
use Psr\Http\Message\ResponseInterface;

class HTTP
{
    /**
     * @var ClientInterface Guzzle HTTP client.
     */
    private ClientInterface $client;

    /**
     * Allowed HTTP methods.
     */
    private const array VALID_METHODS = [
        'GET',
        'PUT',
        'POST',
        'PATCH',
        'DELETE',
        'OPTIONS',
    ];


/* CONSTRUCTOR
----------------------------------------------------------------------------- */

    /**
     * @param Config $config Configuration data object.
     * @param ?ClientInterface $client Guzzle client for mocking.
     */
    public function __construct(
        readonly private Config $config,
        ?ClientInterface $client = null,
    )
    {
        $this->client = $client ?? new GuzzleClient(
            array_merge(
                [ 'timeout' => 20, 'verify' => false ],
                $this->config->options,
                [
                    'base_uri' => rtrim(
                        string: (string)$this->config->host, characters: '/'
                    ) . '/',
                    'http_errors' => false,
                    'headers'     => [
                        'Accept'        => 'application/json; charset=utf-8',
                        'Authorization' => $this->createAuthHeader()
                    ]
                ]
            )
        );
    }


/* SEND HTTP REQUEST
----------------------------------------------------------------------------- */

    /**
     * @param string $path Endpoint URI path.
     * @param string $method HTTP method.
     * @param array<string, string|int|float|bool> $query HTTP query parameters.
     * @param array<string, mixed> $body HTTP POST body content.
     * @return Response
     * @throws HttpException
     * @throws GuzzleException
     */
    public function request(
        string $path,
        string $method  = 'GET',
         array $query    = [],
         array $body     = [],
    ) : Response
    {
        $method = strtoupper( string: $method );

        if( !in_array(
            needle: $method, haystack: self::VALID_METHODS, strict: true )
        ) {
            throw new HttpException(  message: "Invalid HTTP method: {$method}" );
        }

        $path = self::buildPath( path: $path, query: $query );

        $options = [ 'query' => $query ];
        if( !empty( $body ) ) { $options['json'] = $body; }

        return self::buildResponse( response: $this->client->request(
             method: $method,
                uri: $path,
            options: $options
        ));
    }



/* BUILD URI PATH
----------------------------------------------------------------------------- */

    /**
     * Replaces any variable tokens in URI path and replaces with values.
     *
     * @param string $path HTTP URI path.
     * @param array<string, string|int|float|bool> $query HTTP query and path
    parameters.
     * @return string Interpolated URI path.
     */
    private static function buildPath( string $path, array &$query ): string
    {
        $path = ltrim( string: $path, characters: '/' );
        if( str_starts_with( haystack: $path, needle: 'api/' ) ) {
            $path = substr( string: $path, offset: 4 );
        }
        $path = rtrim( string: $path, characters: '/' ) . '/';

        if( !str_contains( haystack: $path, needle: '{' )) {
            return $path;
        }

        foreach( $query as $key => $value )
        {
            if( str_contains( haystack: $path, needle: "{{$key}}" )) {
                $path = str_replace(
                    search: "{{$key}}", replace: (string)$value, subject: $path
                );
                unset( $query[$key] );
            }
        }

        return $path;
    }



/* BUILD HTTP RESPONSE OBJECT
----------------------------------------------------------------------------- */

    /**
     * @param ResponseInterface $response Guzzle PSR Response object.
     * @return Response API client response object.
     * @throws HttpException
     */
    private static function buildResponse( ResponseInterface $response ): Response
    {
        $body = [];
        $status = $response->getStatusCode();
        if( $status !== 204 ) {
            $body = json_decode( $response->getBody()->getContents());
            if( !is_object( $body ) AND !is_array( $body )) {
                throw new HttpException(
                    message: "API: Unexpected response format. " . gettype( $body )
                );
            }
        }
        return new Response(
            status:        $status,
            statusMessage: $response->getReasonPhrase(),
            headers:       $response->getHeaders(),
            body:          $body,
        );
    }



/* CREATE AUTHORIZATION HEADER
----------------------------------------------------------------------------- */

    /**
     * @return string Authorization header.
     */
    private function createAuthHeader() : string
    {
        if( $this->config->auth === 2 ) {
            return 'Bearer nbt_'
                . $this->config->key . '.'
                . $this->config->token;
        }

        return 'Token ' . $this->config->token;
    }
}