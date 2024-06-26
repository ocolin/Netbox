<?php

declare( strict_types = 1 );

namespace Cruzio\lib\Netbox\Data\DCIM;

use Cruzio\lib\Netbox\Data\Data_Core;
use Cruzio\lib\Netbox\Data\DataInterface;
use Cruzio\lib\Netbox\Types\TagType;
use Cruzio\lib\Netbox\Validation;

class InventoryItemRoles extends Data_Core implements DataInterface
{
    /**
     * @var string $name
     * REQUIRED
     * Name of Item
     */
    protected string $name;

    /**
     * @var string $slug
     * REQUIRED
     * URL friendly name for item
     */
    protected string $slug;

    /**
     * @var string $color
     * Name of item color
     */
    protected string $color;

    /**
     * @var string $description
     * Long description
     */
    protected string $description;

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
    protected int    $inventoryitem_count;



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
            'inventoryitem_count'
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
            'name'        => [ 'MaxString', 100 ],
            'slug'        => [ 'Slug', 100 ],
            'color'       => [ 'MaxString', 6 ],
            'description' => [ 'MaxString', 200 ],
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
  "slug": "S8xoE5diseEP7hYDAjuPWU0Gamb4PNWT_aoU78gLAiLNkaN2-wOQAK0Y_eSR7EJhtZ0bpRChKIv0VyUfucJlgCo",
  "color": "1fc690",
  "description": "string",
  "tags": [
    {
      "name": "string",
      "slug": "bVxNCUsKcToi4FB7GC690gKw80vA",
      "color": "883ae5"
    }
  ],
  "custom_fields": {
    "additionalProp1": "string",
    "additionalProp2": "string",
    "additionalProp3": "string"
  }
}
 */