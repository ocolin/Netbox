<?php

declare( strict_types = 1 );

namespace Cruzio\lib\Netbox\Data\DCIM;

use Cruzio\lib\Netbox\Data\Data_Core;
use Cruzio\lib\Netbox\Data\DataInterface;
use Cruzio\lib\Netbox\Types\TagType;
use Cruzio\lib\Netbox\Validation;

class Regions extends Data_Core implements DataInterface
{
    /**
     * @var string $name
     * REQUIRED
     * Name of region
     */
    protected string $name;

    /**
     * @var string $slug
     * REQUIRED
     * URL friendly name of region
     */
    protected string $slug;

    /**
     * @var int $parent
     * ID of a parent region
     */
    protected int $parent;

    /**
     * @var string $description
     */
    protected string $description;

    /**
     * @var array<TagType>
     */
    protected array  $tags;
    protected object  $custom_fields;

    // READ ONLY
    protected int    $id;
    protected int    $_depth;
    protected int    $site_count;
    protected string $url;
    protected string $display;
    protected string $created;
    protected string $last_updated;



/* REQUIRED PARAMETERS
----------------------------------------------------------------------------- */

/**
 *  @return array<string> 
 */

    public static function required() : array
    {
        return [ 'name', 'slug' ];
    }



/* VALIDATE PARAMETERS
----------------------------------------------------------------------------- */

/**
 *  @return array<string, array<string|int>>
 */

    public static function validate() : array
    {
        return [
            'status'        => [ 'Status' ],
            'slug'          => [ 'Slug', 100 ],
            'name'          => [ 'MaxString', 100 ],
            'description'    => [ 'MaxString', 200 ],
        ];
    }

    use Validation\Status;
    use Validation\Slug;
    use Validation\MaxString;


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
            'site_count',
            '_depth',
        ];
    }
}

/* DATA EXAMPLE
----------------------------------------------------------------------------- */

/**
 x`{
  "name": "string",
  "slug": "ScgUXLtHrik4Et7evUBWnKTYju9Rk8KN7fYyzyCB7IBshFobeTis",
  "parent": 0,
  "description": "string",
  "tags": [
    {
      "name": "string",
      "slug": "lNFXa30MX5HrU6wt_mP-Spxo5Ng79oxpsEeuHLPdhnmDBnqLVNKxBEId3R5ytcGtMFKHUxRu5UezQliQTYMpGrUZu",
      "color": "9cbddf"
    }
  ],
  "custom_fields": {
    "additionalProp1": "string",
    "additionalProp2": "string",
    "additionalProp3": "string"
  }
}
 */