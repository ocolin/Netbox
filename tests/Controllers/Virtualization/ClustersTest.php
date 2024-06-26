<?php

declare( strict_types = 1 );

namespace Tests\Controllers\Virtualization;

require_once __DIR__ . '/../DCIM/TestDCIM.php';

use Cruzio\lib\Netbox\Controllers\Virtualization\Clusters;
use Exception;
use PHPUnit\Framework\Attributes\Depends;
use Tests\Controllers\DCIM\TestDCIM;

final class ClustersTest extends TestVirtualization
{
    public static object $site;
    public static object $type;
    public static object $group;

/* OPTIONS TEST
----------------------------------------------------------------------------- */

    public function testOptionsCluster() : void
    {
        $o = new Clusters();
        $result = $o->options();
        $this->assertIsObject( $result );
    }


/* BAD CREATE TEST
----------------------------------------------------------------------------- */

    public function testCreateClusterBad() : void
    {
        $o = new Clusters();
        $result = $o->create( data: [] );

        $this->assertIsObject( $result );
        $this->assertObjectHasProperty( 'error', $result );
        $this->assertIsString( $result->error );
    }


/* CREATE TEST
----------------------------------------------------------------------------- */

    /**
     * @throws Exception
     */
    public function testCreateCluster() : int
    {
        $result = self::createCluster(
            site_id:  self::$site->id,
            type_id:  self::$type->id,
            group_id: self::$group->id
        );
        $this->assertIsObject( $result );
        $this->assertObjectHasProperty( 'id', $result );
        $this->assertIsInt( $result->id );

        return $result->id;
    }


/* GET TEST
----------------------------------------------------------------------------- */

    #[Depends('testCreateCluster')]
    public function testGetCluster( int $id ) : void
    {
        $o = new Clusters();
        $result = $o->get( id: $id );

        $this->assertIsObject( $result );
        $this->assertObjectHasProperty( 'id', $result );
        $this->assertIsInt( $result->id );
    }


/* GET LIST TEST
----------------------------------------------------------------------------- */

    public function testGetListCluster() : void
    {
        $o = new Clusters();
        $result = $o->get();

        $this->assertIsObject( $result );
        $this->assertObjectHasProperty( 'count', $result );
        $this->assertObjectHasProperty( 'next', $result );
        $this->assertObjectHasProperty( 'previous', $result );
        $this->assertObjectHasProperty( 'results', $result );
        $this->assertIsArray( $result->results );
        $this->assertIsObject( $result->results[0] );
        $this->assertObjectHasProperty( 'id', $result->results[0] );
    }


/* BAD REPLACE TEST
----------------------------------------------------------------------------- */

    /**
     * @throws Exception
     */
    #[Depends('testCreateCluster')]
    public function testReplaceClusterBad( int $id ) : void
    {
        $o = new Clusters();
        $result = $o->replace( data: [], id: $id );

        $this->assertIsObject( $result );
        $this->assertObjectHasProperty( 'error', $result );
        $this->assertIsString( $result->error );
    }


/* REPLACE TEST
----------------------------------------------------------------------------- */

    /**
     * @throws Exception
     */
    #[Depends('testCreateCluster')]
    public function testReplaceCluster( int $id ) : void
    {
        $o = new Clusters();
        $result = $o->replace( data: [
            'name' => 'PHPUnit_Replace',
            'site' =>  self::$site->id,
            'type' =>  self::$type->id,
            'group' => self::$group->id
        ], id: $id );

        $this->assertIsObject( $result );
        $this->assertObjectHasProperty( 'id', $result );
        $this->assertIsInt( $result->id );
    }



/* UPDATE TEST
----------------------------------------------------------------------------- */

    /**
     * @throws Exception
     */
    #[Depends('testCreateCluster')]
    public function testUpdateCluster( int $id ) : void
    {
        $o = new Clusters();
        $result = $o->update( data: [
            'name' => 'PHPUnit_Update',
        ], id: $id );

        $this->assertIsObject( $result );
        $this->assertObjectHasProperty( 'id', $result );
        $this->assertIsInt( $result->id );
    }



/* DELETE TEST
----------------------------------------------------------------------------- */

    #[Depends('testCreateCluster')]
    public function testDeleteCluster( int $id ) : void
    {
        $o = new Clusters();
        $result = $o->delete( id: $id );

        $this->assertNull( $result );
    }



/* SETUP
---------------------------------------------------------------------------- */

    /**
     * @throws Exception
     */
    public static function setUpBeforeClass() : void
    {
        self::$site  = TestDCIM::createSite();
        self::$type  = self::createClusterType();
        self::$group = self::createClusterGroup();

    }

/* TEAR DOWN
---------------------------------------------------------------------------- */

    public static function tearDownAfterClass() : void
    {
        TestDCIM::removeSite( id: self::$site->id );
        self::removeClusterType( id: self::$type->id );
        self::removeClusterGroup( id: self::$group->id );
        sleep(1);
    }
}