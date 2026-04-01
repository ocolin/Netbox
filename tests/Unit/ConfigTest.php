<?php

declare( strict_types = 1 );

namespace Ocolin\Netbox\Tests\Unit;

use Ocolin\Netbox\Config;
use Ocolin\Netbox\Exceptions\ConfigException;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{

/* FROM ENV VARS
----------------------------------------------------------------------------- */

    public function test_config_loads_from_env_vars() : void
    {
        $config = new Config();

        $this->assertSame( expected: 'http://localhost:8000/api/', actual: $config->host  );
        $this->assertSame( expected: 'abcdefg',                    actual: $config->token );
        $this->assertSame( expected: 'abcd',                       actual: $config->key   );
        $this->assertSame( expected: 2,                            actual: $config->auth  );
    }



/* EXPLICIT ARGUMENTS OVERRIDE ENV VARS
----------------------------------------------------------------------------- */

    public function test_explicit_arguments_override_env_vars() : void
    {
        $config = new Config(
            host:  'https://netbox.example.com/api/',
            auth:  2,
            token: 'mytoken',
            key:   'mykey',
        );

        $this->assertSame( expected: 'https://netbox.example.com/api/', actual: $config->host  );
        $this->assertSame( expected: 'mytoken',                         actual: $config->token );
        $this->assertSame( expected: 'mykey',                           actual: $config->key   );
        $this->assertSame( expected: 2,                                 actual: $config->auth  );
    }



/* AUTH DEFAULTS TO 2
----------------------------------------------------------------------------- */

    public function test_auth_defaults_to_2_when_not_provided() : void
    {
        $config = new Config(
            host:  'https://netbox.example.com',
            auth:  null,
            token: 'mytoken',
            key:   'mykey',
        );

        $this->assertSame( expected: 2, actual: $config->auth );
    }



/* OPTIONS DEFAULT TO EMPTY ARRAY
----------------------------------------------------------------------------- */

    public function test_options_defaults_to_empty_array() : void
    {
        $config = new Config(
            host:  'https://netbox.example.com',
            token: 'mytoken',
            key:   'mykey',
        );

        $this->assertSame( expected: [], actual: $config->options );
    }



/* CUSTOM OPTIONS ARE STORED
----------------------------------------------------------------------------- */

    public function test_custom_options_are_stored() : void
    {
        $config = new Config(
            host:    'https://netbox.example.com',
            token:   'mytoken',
            key:     'mykey',
            options: [ 'timeout' => 30, 'verify' => true ]
        );

        $this->assertSame(
            expected: [ 'timeout' => 30, 'verify' => true ],
            actual:   $config->options
        );
    }



/* MISSING HOST THROWS EXCEPTION
----------------------------------------------------------------------------- */

    public function test_missing_host_throws_config_exception() : void
    {
        $this->expectException( ConfigException::class );
        $this->expectExceptionMessage( 'Missing Netbox host name.' );

        $_ENV['NETBOX_API_HOST'] = null;
        $test = new Config(
             host:  null,
            token: 'mytoken',
              key: 'mykey',
        );
    }



/* INVALID AUTH VERSION THROWS EXCEPTION
----------------------------------------------------------------------------- */

    public function test_invalid_auth_version_throws_config_exception() : void
    {
        $this->expectException( ConfigException::class );
        $this->expectExceptionMessage( 'AUTH: Missing Authentication method.' );

        new Config(
            host:  'https://netbox.example.com',
            auth:  99,
            token: 'mytoken',
            key:   'mykey',
        );
    }



/* V1 AUTH MISSING TOKEN THROWS EXCEPTION
----------------------------------------------------------------------------- */

    public function test_v1_auth_missing_token_throws_config_exception() : void
    {
        $this->expectException( ConfigException::class );
        $this->expectExceptionMessage( 'Missing Netbox token.' );

        $_ENV['NETBOX_API_TOKEN'] = null;

        new Config(
            host:  'https://netbox.example.com',
            auth:  1,
            token: null
        );
    }



/* V2 AUTH MISSING TOKEN THROWS EXCEPTION
----------------------------------------------------------------------------- */

    public function test_v2_auth_missing_token_throws_config_exception() : void
    {
        $this->expectException( ConfigException::class );
        $this->expectExceptionMessage( 'Missing Netbox token or key.' );

        $_ENV['NETBOX_API_KEY'] = null;

        new Config(
            host:  'https://netbox.example.com',
            auth:  2,
            token: null,
            key:   'mykey'
        );
    }



/* V2 AUTH MISSING KEY THROWS EXCEPTION
----------------------------------------------------------------------------- */

    public function test_v2_auth_missing_key_throws_config_exception() : void
    {
        $this->expectException( ConfigException::class );
        $this->expectExceptionMessage( 'Missing Netbox token or key.' );

        new Config(
            host:  'https://netbox.example.com',
            auth:  2,
            token: 'mytoken',
            key:   null,
        );
    }



/* V1 AUTH DOES NOT REQUIRE KEY
----------------------------------------------------------------------------- */

    public function test_v1_auth_does_not_require_key() : void
    {
        $config = new Config(
            host:  'https://netbox.example.com',
            auth:  1,
            token: 'mytoken',
            key:   null,
        );

        $this->assertSame( expected: 1, actual: $config->auth );
        $this->assertNull( actual: $config->key );
    }
}