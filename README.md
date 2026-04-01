# Netbox

## What is it?

This is a small PHP client for the Netbox API REST services.

---

## Requirements

- PHP >=8.3
- guzzlehttp/guzzle 7.10
- ocolin/global-type ^2.0

---

## Installation

```
composer require ocolin/netbox
```

---

## TODO

- Add an images upload function
- Integration testing for Netbox 3.

---

## Configuration

The client can be configured in two ways. Via environment variables, and constructor arguments.

### Properties

|Environment Name| Argument Name | Type    | Default | Description                   |
|----------------|---------------|---------|---------|-------------------------------|
|NETBOX_API_HOST| $host         | string  | N/A     | Hostname of Netbox server     |
|NETBOX_API_AUTH| $auth         | integer | 2       | Authentication version 1 or 2 |
|NETBOX_API_TOKEN| $token        | string  | N/A     | API Auth token                |
|NETBOX_API_KEY| $key          | string  | N/A     | API Key for version 2 auth    |

**Note**: Version 2 authentication takes both a key and a token, while Version 1 only uses a token.

### Environment Variables

```php
// Manually creating for demonstration
$_ENV['NETBOX_API_HOST']  = 'http://localhost:8000';
$_ENV['NETBOX_API_AUTH']  = 2;
$_ENV['NETBOX_API_TOKEN'] = 'abcdefg';
$_ENV['NETBOX_API_KEY']   = 'abcdefg';

$netbox = new \Ocolin\Netbox\Netbox();
```

### Constructor arguments

The constructor takes a configuration object that allows you to add your configuration settings. These can also be used in conjunction with environment variables. Any missing parameters will be checked in the Environment. 

```php
$netbox = new Ocolin\Netbox\Netbox(
    config: new Ocolin\Netbox\Config(
           host: 'http://localhost:8000',
           auth: 2,
          token: 'abcdefg',
            key: 'abcdefg',
        options: [
            'verify' => true
        ] 
    )
);
```

### Options

As can be seen in previous example, there is an options parameter. This allows you to specify Guzzle HTTP client settings if needed. This can be useful if you want to enable SSL verification, or HTTP timeout, etc. The client defaults to SSL verification off.

### HTTP defaults

| Option  | Default | Description                  |
|---------|---------|------------------------------|
| timeout | 20      | Seconds to give HTTP attempt |
| verify  | false   | Verify SSL credentials       |

---

## Response

The client outputs a response object with the following parameters:

| Property Name | Type    | Description                |
|---------------|---------|----------------------------|
| status        | integer | HTTP status code           |
| statusMessage | string  | HTTP status message        |
| headers       | array   | List of response headers   |
| body          | mixed   | HTTP response body content |

---

## Method functions

### Path interpolation

Any {tokens} found in the endpoint path will be replaced with matching
keys from the $query array. Those keys are then removed from the query
string automatically.

**Note**: Netbox requires a trailing slash on all endpoint paths.
The client adds one automatically if missing.

### GET

Get a resource

```php
$output = $netbox->get(
    endpoint: '/api/dcim/sites/{id}/',
       query: [ 'id' => 123 ]
);
```

### POST

Create a new resource

```php
$output = $netbox->post(
    endpoint: '/api/dcim/sites/',
    body: [
        'name' => 'My Site',
        'slug' => 'My-Site'
    ]
);
```

### PATCH

Update a value in a resource

```php
$output = $netbox->patch(
    endpoint: '/api/dcim/sites/{id}/',
       query: [ 'id' => 123 ],
        body: [ 'name' => 'My Updated Name' ]
);
```

### PUT

Update/Replace an entire resource

```php
$output = $netbox->put(
    endpoint: '/api/dcim/sites/{id}/',
       query: [ 'id' => 123 ],
        body: $newBody
);
```

### DELETE

Delete a resource

```php
$output = $netbox->delete(
    endpoint: '/api/dcim/sites/{id}/',
       query: [ 'id' => 123 ]
);
```

### OPTIONS

OPTIONS returns information about an endpoint.

```php
$output = $netbox->options( endpoint: '/api/dcim/sites/' );
```