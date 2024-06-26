<?php

declare( strict_types = 1 );

namespace Cruzio\lib\Netbox\Data;

use Exception;
use ReflectionClass;
use ReflectionProperty;
use stdClass;

class Data_Core
{

/* RENDER DATA INTO A SIMPLE OBJECT
----------------------------------------------------------------------------- */

    /**
     * @param bool $required Check for required fields
     * @return object
     * @throws Exception
     */
    public function render( bool $required = false ) : object
    {
        $reflect = new ReflectionClass( objectOrClass: $this );
        $props   = $reflect->getProperties( filter: ReflectionProperty::IS_PROTECTED );
        $obj = new stdClass();
        foreach( $props as $prop )
        {
            $name = $prop->getName();
            
            if( $prop->isInitialized( object: $this )) {
                $obj->$name = $this->$name;
            }
            
            elseif(
                $required === true AND
                in_array( needle: $name, haystack: static::required())
            ) {
                throw new Exception( message:"Property '$name' is required" );
            }
        }

        return $obj;
    }



/* SET A DATA PARAMETER
----------------------------------------------------------------------------- */

    /**
     * @param string $property
     * @param int|string|float|object|array<string|int> $value
     * @throws Exception
     */
    public function set( string $property, int|string|float|object|array $value ) : void
    {
        if( property_exists( $this, property: $property )) {
            $rp = new ReflectionProperty( $this, $property );
            if( !$rp->isPrivate() ) {
                if( array_key_exists( key: $property, array: $this->validate())) {
                    self::build_Validate( property: $property, value: $value );
                }
                $this->$property = $value;
            }
        }

    }



/* BUILD VALIDATION FUNCTIONS
----------------------------------------------------------------------------- */

    /**
     * @param string $property
     * @param string|int|float|object $value
     * @throws Exception
     *
     */
    protected static function build_Validate( string $property, string|int|float|object $value ) : void
    {
        $params = static::validate()[$property];
        $val_func =  'validate_' . array_shift( array: $params );
        $count = count( value: $params );

        switch( $count ) {
            case 0:
                $result = static::$val_func( $value );
                break;
            case 1:
                $param1 = array_shift(array: $params );
                $result = static::$val_func( $value, $param1 );
                break;
            case 2:
                $param1 = array_shift( array: $params );
                $param2 = array_shift( array: $params );
                $result = static::$val_func( $value, $param1, $param2 );
                break;
            default:
                $result = static::$val_func( $value );
        }

        if(  $result !== true ) {
            throw new Exception( $result );
        }

    }



/* GET A DATA PARAMETER
----------------------------------------------------------------------------- */

    public function get( string $property ) : mixed
    {
        if( property_exists( $this, property: $property )) {
            $rp = new ReflectionProperty( class: $this, property: $property );
            if( !$rp->isPrivate() ) {
                return $this->$property;
            }
        }

        return null;
    }



/* GET REQUIRED PROPERTIES
----------------------------------------------------------------------------- */

/**
 *  @return array<string>
 */
    public static function required() : array
    {
        return [];
    }



/* GET PROPERTY VALIDATION RULES
----------------------------------------------------------------------------- */

/**
 *  @return array<string, array<string>>
 */

    public static function validate() : array
    {
        return [];
    }



/* GET ALL READ ONLY PROPERTIES
----------------------------------------------------------------------------- */

/**
 *  @return array<string>
 */

    public static function readonly() : array
    {
        return [];
    }

}

