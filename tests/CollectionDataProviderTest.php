<?php

use Nayjest\Grids\CollectionDataProvider;
use Nayjest\Grids\EloquentDataRow;
use Nayjest\Grids\Grids;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

class CollectionDataProviderTest extends TestCase
{


    /**
     * Creates the fake collectiondataprovider for testing
     *
     * @return Nayjest\Grids\CollectionDataProvider
     */
    private function getTestCollectionDataProvider()
    {
        $models = factory(App\User::class, 3)->make();
        return new CollectionDataProvider($models);
    }

    /**
     * Create a Collection Data Provider
     *
     * @return \Nayjest\Grids\CollectionDataProvider
     */
    public function test_create_CollectionDataProvider()
    {
        $this->assertInstanceOf(CollectionDataProvider::class,$this->getTestCollectionDataProvider());
    }


    /**
     * we should return a paginator
     *
     * @return \Illuminate\Pagination\Paginator
     */
    public function test_CollectionDataProvider_has_paginator()
    {
      $this->assertInstanceOf(Paginator::class,$this->getTestCollectionDataProvider()->getPaginator());
    }

    /**
     * we should return a collection
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function test_CollectionDataProvider_has_collection()
    {
      $this->assertInstanceOf(Collection::class,$this->getTestCollectionDataProvider()->getCollection());
    }


    /**
     * Test Reset Method doesn't throw an error
     *
     * @return \Nayjest\Grids\CollectionDataProvider
     */
    public function test_reset()
    {
        $this->assertInstanceOf(CollectionDataProvider::class,$this->getTestCollectionDataProvider()->reset());
    }

    /**
     * Test getPagination Method doesn't throw an error
     *
     * @return Illuminate\Pagination\Paginator
     */
    public function test_getPaginationFactory()
    {
        $this->assertInstanceOf(Paginator::class,$this->getTestCollectionDataProvider()->getPaginationFactory());
    }


    /**
     * Test getRow Method doesn't throw an error
     *
     * @return \Nayjest\Grids\EloquentDataRow
     */
    public function test_getRow()
    {
        $this->assertInstanceOf(EloquentDataRow::class,$this->getTestCollectionDataProvider()->getRow());
    }


    /**
     * Test count Method
     *
     * @return Int
     */
    public function test_count()
    {
        $this->assertEquals(3,$this->getTestCollectionDataProvider()->count());
    }


    /**
     * Test OrderBy Method doesn't throw an error
     *
     * @return \Nayjest\Grids\CollectionDataProvider
     */
    public function test_orderby()
    {
        $this->assertInstanceOf(CollectionDataProvider::class,$this->getTestCollectionDataProvider()->orderBy('name','asc'));
    }

    /**
     * Test filter Method doesn't throw an error
     *
     * @return \Nayjest\Grids\CollectionDataProvider
     */
    public function test_filter()
    {
        $this->assertInstanceOf(CollectionDataProvider::class,$this->getTestCollectionDataProvider()->filter('name','cat','hat'));
    }

}
