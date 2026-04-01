<?php

declare( strict_types = 1 );

namespace Ocolin\Netbox;

use Ocolin\Netbox\Exceptions\ConfigException;
use Ocolin\GlobalType\ENV;

readonly class Config
{
    /**
     * @var ?string Host and URI of API server.
     */
    public ?string $host;

    /**
     * @var ?int Auth version 1 or 2.
     */
    public ?int $auth;

    /**
     * @var ?string Authorization token.
     */
    public ?string $token;

    /**
     * @var ?string Version 2 authorization key.
     */
    public ?string $key;

    /**
     * @var array<string, mixed> List of Guzzle options.
     */
    public array $options;


/* CONSTRUCTOR
----------------------------------------------------------------------------- */

    /**
     * @param ?string $host Hostname and URI of API server.
     * @param ?int $auth Authentication version (1 or 2)
     * @param ?string $token API token.
     * @param ?string $key Auth version 2 key.
     * @param array<string, mixed> $options Guzzle options.
     * @throws ConfigException
     */
    public function __construct(
        ?string $host    = null,
           ?int $auth    = null,
        ?string $token   = null,
        ?string $key     = null,
          array $options = []
    )
    {
        $this->host = self::normalizeHost(
            $host ?? ENV::getStringNull( name: 'NETBOX_API_HOST' )
        );
        $this->token   = $token ?? ENV::getStringNull( name: 'NETBOX_API_TOKEN' );
        $this->key     = $key   ?? ENV::getStringNull( name: 'NETBOX_API_KEY'   );
        $this->auth    = $auth  ?? ENV::getIntNull(    name: 'NETBOX_API_AUTH'  ) ?? 2;
        $this->options = $options;

        $this->validateHost();
        $this->validateAuth();

        match( $this->auth ) {
            1       => $this->validateOne(),
            2       => $this->validateTwo(),
            default => null
        };
    }



/* VALIDATE HOST
----------------------------------------------------------------------------- */

    /**
     * Check that Netbox server hostname has been provided.
     *
     * @return void
     * @throws ConfigException
     */
    private function validateHost() : void
    {
        if(  $this->host === null ) {
            throw new ConfigException( message: "Missing Netbox host name." );
        }
    }


/* VALIDATE AUTH VERSION
----------------------------------------------------------------------------- */

    /**
     * Check that an API authentication method is specified.
     *
     * @return void
     * @throws ConfigException
     */

    private function validateAuth() : void
    {
        $auths = [ 1,2 ];

        if( in_array( needle: $this->auth, haystack: $auths, strict: true ) === false )
        {
            throw new ConfigException(
                message: "AUTH: Missing Authentication method."
            );
        }
    }



/* VALIDATE AUTH VERSION ONE
----------------------------------------------------------------------------- */

    /**
     * @return void
     * @throws ConfigException
     */
    private function validateOne() : void
    {
        if( $this->token === null ) {
            throw new ConfigException( message: "Missing Netbox token." );
        }
    }



/* VALIDATE AUTH VERSION TWO
----------------------------------------------------------------------------- */

    /**
     * @return void
     * @throws ConfigException
     */
    private function validateTwo() : void
    {
        if( $this->key === null OR $this->token === null ) {
            throw new ConfigException( message: "Missing Netbox token or key." );
        }
    }


/* FORMAT HOST PARAMETER
----------------------------------------------------------------------------- */

    private static function normalizeHost( ?string $host ) : ?string
    {
        if( $host === null ) return null;
        if( !str_ends_with( haystack: $host, needle: '/api/' ) ) {
            $host = rtrim( string: $host, characters: '/' ) . '/api/';
        }

        return $host;
    }
}