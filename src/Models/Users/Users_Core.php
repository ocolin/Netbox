<?php

declare( strict_types=1 );

namespace Cruzio\lib\Netbox\Models\Users;

use Cruzio\lib\Netbox\Models\HTTP;
use Cruzio\lib\Netbox\Models\Models_Core;
use Cruzio\lib\Netbox\Models\ModelsInterface;

abstract class Users_Core extends Models_Core implements ModelsInterface
{
    protected string $uri = 'users/';

    protected HTTP $http;

    public function __construct( ?HTTP $http = null )
    {
        $this->uri = 'users/';
        $this->http = $http ?? new HTTP();
        parent::__construct( http: $http );
    }

}
