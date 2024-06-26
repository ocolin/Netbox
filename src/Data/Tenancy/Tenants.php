<?php

declare( strict_types = 1 );

namespace Cruzio\lib\Netbox\Data\Tenancy;

use Cruzio\lib\Netbox\Data\Data_Core;
use Cruzio\lib\Netbox\Data\DataInterface;
use Cruzio\lib\Netbox\Types\TagType;
use Cruzio\lib\Netbox\Validation;

class Tenants extends Data_Core implements DataInterface
{
    /**
     * @var string $name
     * REQUIRED
     */
    protected string $name;

    /**
     * @var string $slug
     * REQUIRED
     */
    protected string $slug;

    /**
     * @var int $group
     * ID of ContactGroups class
     */
    protected int $group;

    /**
     * @var string $description
     * Long description
     */
    protected string $description;

    /**
     * @var string $comments
     */
    protected string $comments;
    protected object $custom_fields;

    /**
     * @var array<TagType> $tags
     */
    protected array $tags;

    // READ ONLY
    protected int    $id;
    protected string $url;
    protected string $display;
    protected string $created;
    protected string $last_updated;
    protected int    $circuit_count;
    protected int    $device_count;
    protected int    $ipaddress_count;
    protected int    $prefix_count;
    protected int    $rack_count;
    protected int    $site_count;
    protected int    $virtualmachine_count;
    protected int    $vlan_count;
    protected int    $vrf_count;
    protected int    $cluster_count;



/* REQUIRED PARAMETERS
----------------------------------------------------------------------------- */

/**
 *  @return array<string> 
 */

    public static function required() : array
    {
        return [ 'name', 'slug' ];
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
            'circuit_count',
            'device_count',
            'ipaddress_count',
            'prefix_count',
            'rack_count',
            'site_count',
            'virtualmachine_count',
            'vlan_count',
            'vrf_count',
            'cluster_count'
        ];
    }


/* VALIDATE PARAMETERS
----------------------------------------------------------------------------- */

    /**
     * @return array<string, array<string|int>>
     */
    public static function validate() : array
    {
        return [
            'name'          => [ 'MaxString', 100 ],
            'slug'          => [ 'Slug', 100 ],
            'description'   => [ 'MaxString', 200 ],

        ];
    }

    use Validation\Slug;
    use Validation\MaxString;
}

/* DATA EXAMPLE
----------------------------------------------------------------------------- */

/**
{
  "name": "string",
  "slug": "GtKnfpEpqwoZi7FS1H6rLhPPMHrCK9CyRlNAHq8YQTkkBhL4bNUzINdf70xOiyUu9ylke_",
  "group": 0,
  "description": "string",
  "comments": "string",
  "tags": [
    {
      "name": "string",
      "slug": "ERnMLyHEsPpsKIc_v1h8ydbhVpuDP2Bqw-_tJ-qPXE",
      "color": "c2ebb1"
    }
  ],
  "custom_fields": {
    "additionalProp1": "string",
    "additionalProp2": "string",
    "additionalProp3": "string"
  }
}
*/