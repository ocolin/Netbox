# Netbox

## Description

This is a basic client for the Netbox API. It uses the EasySwagger library, but contains the UISP swagger JSON file.

## Usage

### Environment variables

You can specify parameters in the constructor, but if any are left out, these environment variables will be used instead.

NETBOX_API_TOKEN - Authentication token for API

NETBOX_API_HOST - URL of server API

### Instantiate

Create an instance of the Netbox object.

```
$netbox = new Ocolin\Netbox\Netbox();
```

#### Parameters

$host: Name of the Netbox host server. If null, will use .env field.

$api_key: Authentication token for server. If null, will use .env field.

$api_file: Path to Swagger JSON file. If null, will use included file.

$local: If true, it will try to load .env file in root directory.

### Making a call

```
$output = $netbox->path( 
    path: '/dcim/devices/{id}',
    data: [
        'id' => 'f700f200-f27f-442b-b086-c6ea128953b7',
    ] 
);
```

#### Parameters

$path: REQUIRED - Swagger call path, including named parameters.

$data: Array of parameters name/values to use for URI path or body.

$method: HTTP method. Defaults to GET.