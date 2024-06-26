<?php

namespace Cruzio\lib\Netbox\Validation;

trait IpRole
{

/* VALIDATE
----------------------------------------------------------------------------- */

    public static function validate_IpRole( string $input ) : true|string
    {
        $allowed = [
            'loopback',
            'secondary',
            'anycast',
            'vip',
            'vrrp',
            'hsrp',
            'glbp',
            'carp'
        ];
        if( !in_array( needle: $input, haystack: $allowed )) {
            $combined = implode( separator: ',', array: $allowed );
            return "IpRole '$input' must be of type: $combined.";
        }

        return true;
    }
}
