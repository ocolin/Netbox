#!/opt/homebrew/Cellar/php/8.3.3_1/bin/php
<?php

declare( strict_types = 1 );

require_once __DIR__ . '/vendor/autoload.php';

use Ocolin\Netbox\Netbox;

$netbox = new Netbox( local: true );

$output = $netbox->path(
  path: '/dcim/devices/'
);

print_r( $output );