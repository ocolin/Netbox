<?php

declare( strict_types = 1 );

require_once __DIR__ . '/../vendor/autoload.php';

use Ocolin\EasyEnv\LoadEnv;
use Ocolin\EasyEnv\Errors\EasyEnvInvalidFilePathError;
use Ocolin\EasyEnv\Errors\EasyEnvFileHandleError;

try {
    new LoadEnv( files: __DIR__ . '/../.env', append: true );
}
catch( EasyEnvFileHandleError | EasyEnvInvalidFilePathError $e ) {
    die ( $e->getMessage() );
}