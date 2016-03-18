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
    public function testCreateCollectionDataProvider()
    {
        $this->assertInstanceOf(CollectionDataProvider::class,$this->getTestCollectionDataProvider());
    }


    /**
     * we should return a paginator
     *
     * @return \Illuminate\Pagination\Paginator
     */
    public function testCollectionDataProviderHasPaginator()
    {
      $this->assertInstanceOf(Paginator::class,$this->getTestCollectionDataProvider()->getPaginator());
    }

    /**
     * we should return a collection
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function testCollectionDataProviderHasCollection()
    {
      $this->assertInstanceOf(Collection::class,$this->getTestCollectionDataProvider()->getCollection());
    }


    /**
     * Test Reset Method doesn't throw an error
     *
     * @return \Nayjest\Grids\CollectionDataProvider
     */
    public function testReset()
    {
        $this->assertInstanceOf(CollectionDataProvider::class,$this->getTestCollectionDataProvider()->reset());
    }

    /**
     * Test getPagination Method doesn't throw an error
     *
     * @return Illuminate\Pagination\Paginator
     */
    public function testGetPaginationFactory()
    {
        $this->assertInstanceOf(Paginator::class,$this->getTestCollectionDataProvider()->getPaginationFactory());
    }


    /**
     * Test getRow Method doesn't throw an error
     *
     * @return \Nayjest\Grids\EloquentDataRow
     */
    public function testGetRow()
    {
        $this->assertInstanceOf(EloquentDataRow::class,$this->getTestCollectionDataProvider()->getRow());
    }


    /**
     * Test count Method
     *
     * @return Int
     */
    public function testCount()
    {
        $this->assertEquals(3,$this->getTestCollectionDataProvider()->count());
    }


    /**
     * Test OrderBy Method doesn't throw an error
     *
     * @return \Nayjest\Grids\CollectionDataProvider
     */
    public function testOrderby()
    {
        $this->assertInstanceOf(CollectionDataProvider::class,$this->getTestCollectionDataProvider()->orderBy('name','asc'));
    }

    /**
     * Test filter Method doesn't throw an error
     *
     * @return \Nayjest\Grids\CollectionDataProvider
     */
    public function testFilter()
    {
        $this->assertInstanceOf(CollectionDataProvider::class,$this->getTestCollectionDataProvider()->filter('name','cat','hat'));
    }

}
