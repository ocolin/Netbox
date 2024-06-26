<?php

declare( strict_types = 1 );

namespace Cruzio\lib\Netbox\Data\Virtualization;

use Cruzio\lib\Netbox\Data\Data_Core;
use Cruzio\lib\Netbox\Data\DataInterface;
use Cruzio\lib\Netbox\Types\TagType;
use Cruzio\lib\Netbox\Validation;

class Clusters extends Data_Core implements DataInterface
{
    /**
     * @var string $name
     * REQUIRED
     */
    protected string $name;

    /**
     * @var int $type
     * REQUIRED
     * ID of ClusterTypes class
     */
    protected int $type;

    /**
     * @var int $group
     * ID of ClusterGroups class
     */
    protected int $group;

    /**
     * @var string $status
     * ENUM
     */
    protected string $status;

    /**
     * @var int $tenant
     * ID of Tenants class
     */
    protected int $tenant;

    /**
     * @var int $site
     * ID of Sites class
     */
    protected int $site;

    /**
     * @var string $description
     * Long description
     */
    protected string $description;

    /**
     * @var string $comments
     */
    protected string $comments;

    /**
     * @var array<TagType> $tags
     */
    protected array $tags;
    protected object $custom_fields;


    // READ ONLY
    protected int    $id;
    protected string $url;
    protected string $display;
    protected string $created;
    protected string $last_updated;
    protected int    $device_count;
    protected int    $virtualmachine_count;


/* REQUIRED PARAMETERS
----------------------------------------------------------------------------- */

/**
 *  @return array<string> 
 */

    public static function required() : array
    {
        return [ 'name', 'type' ];
    }



/* READ ONLY PARAMETERS
----------------------------------------------------------------------------- */

/**
 *  @return array<string> 
 */

    public static function readonly() : array
    {
        return [
            'id',
            'url',
            'display',
            'created',
            'last_updated',
            'device_count',
            'virtualmachine_count'
        ];
    }


/* VALIDATE PARAMETERS
----------------------------------------------------------------------------- */

    /**
     *  @return array<string, array<string|int>>
     */

    public static function validate() : array
    {
        return [
            'name'          => [ 'MaxString', 100 ],
            'status'        => [ 'Status' ],
            'description'   => [ 'MaxString', 200 ],
        ];
    }

    use Validation\Status;
    use Validation\MaxString;
}

/* DATA EXAMPLE
----------------------------------------------------------------------------- */

/**
{
  "name": "string",
  "type": 0,
  "group": 0,
  "status": "planned",
  "tenant": 0,
  "site": 0,
  "description": "string",
  "comments": "string",
  "tags": [
    {
      "name": "string",
      "slug": "DNDny6dN2XwoB6E3nYwUOJP4VdDa6JnKiZrHM0exHxHDMZbjhNmvgtX521Wd2dhYekgGQiQ3wqcnd1J5JDBsOeysKV7NGz6v",
      "color": "b7b3bc"
    }
  ],
  "custom_fields": {
    "additionalProp1": "string",
    "additionalProp2": "string",
    "additionalProp3": "string"
  }
}
 */