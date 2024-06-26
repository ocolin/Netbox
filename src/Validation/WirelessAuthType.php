<?php

namespace Cruzio\lib\Netbox\Validation;

trait WirelessAuthType
{
    
/* VALIDATE WIRELESS AUTH TYPE
----------------------------------------------------------------------------- */

    public static function validate_WirelessAuthType( string $input ) : true|string
    {
        $allowed = [ 'open', 'wep', 'wpa-personal', 'wpa-enterprise' ];
        if( !in_array( needle: $input, haystack: $allowed )) {
            $combined = implode( separator: ',', array: $allowed );
            return "WirelessAuthType '$input' should be in: $combined";
        }

        return true;
    }

}
