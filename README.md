# Netbox

## Description

This is a basic REST client for the Netbox API. 

## Usage

### Environment variables

You can specify parameters in the constructor, but if any are left out, these environment variables will be used instead.

NETBOX_API_TOKEN - Authentication token for API

NETBOX_API_HOST - URL of server API

### Instantiate

Create an instance of the Netbox object.

```php
$netbox = new Ocolin\Netbox\Client();
```



#### Parameters

$host: Name of the Netbox host server. If null, will use Env parameter

$token: Authentication token for server. If null, will use Env parameter.

$timeout: HTTP Timeout. Defaults to 20 seconds.

$verify: Verify SSL certificate. Defaults to off.

### Making a call

This method will return just the HTTP body from the server.

```php
$output = $netbox->call( 
    path: '/dcim/devices/{id}',
    query: [
        'id' => 'f700f200-f27f-442b-b086-c6ea128953b7',
    ] 
);
```

#### Parameters

$path: REQUIRED - API endpoint path, including named parameters.

$query: Array/Object of parameters name/values to use for URI path or URI query.

$body: Array/Object for POST/PUT/PATCH HTTP body.

$method: HTTP method. Defaults to GET.

### Making a full call.

Same parametes as the call() function, but different output. This will return an object 4 properties:

- $status: HTTP status code
- $statusMessage: HTTP status message
- $headers: HTTP response headers
- $body: HTTP response body

```php
$output = $netbox->full( 
    path: '/dcim/devices/{id}',
    method: 'DELETE',
    query: [
        'id' => 'f700f200-f27f-442b-b086-c6ea128953b7',
    ] 
);
```