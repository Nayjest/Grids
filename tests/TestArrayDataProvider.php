<?php
use Nayjest\Grids\ArrayDataProvider;
use Nayjest\Grids\Sorters\ArraySorter;

class TestArrayDataProvider extends TestCase
{
    protected function getData() {
        return [
            ['column1' => 'row1', 'column2' => 2, 'column3' => true],
            ['column1' => 'row2', 'column2' => 1, 'column3' => true],
            ['column1' => 'row3', 'column2' => 4, 'column3' => true],
            ['column1' => 'row4', 'column2' => 3, 'column3' => true],
            ['column1' => 'row5', 'column2' => 5, 'column3' => true],
        ];
    }

    protected function createProvider()
    {
        return new ArrayDataProvider($this->getData());
    }

    public function testFetch()
    {
        $provider = $this->createProvider();
        $res = $provider->fetch();
        $this->assertEquals($res, $this->getData());
    }

    public function testOffset()
    {
        $provider = $this->createProvider();
        $res = $provider->fetch(1);
        $this->assertEquals('row2',$res[0]['column1']);
    }

    public function testFiltersUsage()
    {
        $provider = $this->createProvider();
        $sorter = new ArraySorter();
        $sorter->setColumnName('column2');
        $sorter->setOrder(ArraySorter::ASC);
        $provider->setSorter($sorter);
        $res = $provider->fetch(4,1);
        $this->assertEquals('row5',$res[0]['column1']);
    }

}