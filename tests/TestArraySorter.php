<?php
use Nayjest\Grids\Sorters\ArraySorter;

class TestArraySorter extends TestCase
{
    protected function getData()
    {
        return [
            ['column1' => 'row1', 'column2' => 2, 'column3' => true],
            ['column1' => 'row2', 'column2' => 1, 'column3' => true],
            ['column1' => 'row3', 'column2' => 4, 'column3' => true],
            ['column1' => 'row4', 'column2' => 3, 'column3' => true],
            ['column1' => 'row5', 'column2' => 5, 'column3' => true],
        ];
    }

    protected function createInstance()
    {
        $obj = new ArraySorter();
        return $obj;

    }

    public function testSort()
    {
        $obj = $this->createInstance();
        $data = $this->getData();
        $obj->setColumnName('column2');

        $obj->setOrder(ArraySorter::ASC);
        $obj->sort($data);
        $this->assertEquals($data[0]['column2'], 1);
        $this->assertEquals($data[4]['column2'], 5);

        $obj->setOrder(ArraySorter::DESC);
        $obj->sort($data);
        $this->assertEquals($data[1]['column2'], 4);
        $this->assertEquals($data[3]['column2'], 2);
    }


} 