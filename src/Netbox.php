<?php

declare( strict_types = 1 );

namespace Ocolin\Netbox;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Ocolin\Env\EasyEnv;
use Ocolin\EasySwagger\Swagger;

class Netbox
{

    public Swagger $swagger;


/* CONSTRUCTOR
----------------------------------------------------------------------------- */

    /**
     * @param string|null $host     URL of Netbox server.
     * @param string|null $api_key  Netbox API Token.
     * @param string|null $api_file File path to Netbox Swagger JSON file.
     * @param bool $local Load local .env file for Environment variables. False if
     *      they are being loaded by project implementing this one.
     * @throws Exception
     */
    public function __construct(
        ?string $host     = null,
        ?string $api_key  = null,
        ?string $api_file = null,
           bool $local    = false
    ) {
        if( $local === true ) {
            EasyEnv::loadEnv( path: __DIR__ . '/../.env' );
        }
        $host     = $host     ?? $_ENV['NETBOX_API_HOST'];
        $api_key  = $api_key  ?? $_ENV['NETBOX_API_TOKEN'];
        $api_file = $api_file ?? __DIR__ . '/api.v.2.0.json';

        $this->swagger = new Swagger(
                host: $host,
            api_file: $api_file,
            base_uri: '',
               token: $api_key,
            token_name: 'Authorization'
        );
    }



/* QUERY API PATH
----------------------------------------------------------------------------- */

    /**
     * @param string $path      Unaltered API path (including variable placeholders)
     * @param string $method    HTTP method. Defaults to GET.
     * @param array|null $data  Array of any parameters for the URI or body.
     * @return object|array     Reply from server API.
     * @throws GuzzleException
     */
    public function path(
        string $path,
        string $method = 'get',
        ?array $data   = []
    ) : object|array
    {
        return $this->swagger->path(
              path: $path,
            method: $method,
              data: $data );
    }

}