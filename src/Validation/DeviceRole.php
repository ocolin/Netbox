<?php

namespace Cruzio\lib\Netbox\Validation;

trait DeviceRole
{

/* VALIDATE
----------------------------------------------------------------------------- */

    public static function validate_DeviceRole( string $input ) : true|string
    {
        $allowed = [ 'parent','child' ];
        if(!in_array( needle: $input, haystack: $allowed )) {
            return "SubRole '$input' must be either parent of child.";
        }

        return true;
    }

}