<?php
namespace Nayjest\Grids;

use Illuminate\Database\Eloquent\Builder;
use Event;
use Illuminate\Support\Collection;

class EloquentDataProvider extends DataProvider
{


    protected $collection;

    protected $paginator;

    /** @var  $iterator \ArrayIterator */
    protected $iterator;

    public function __construct(Builder $src)
    {
        $this->src = $src;
    }

    public function reset()
    {
        $this->getIterator()->rewind();
        return $this;
    }

    /**
     * @return \Illuminate\Pagination\Paginator
     */
    public function getCollection()
    {
        if (!$this->collection) {
            $paginator = $this->getPaginator();
            if (method_exists($paginator, 'getCollection')) {
                # Laravel 4 compatibility
                $this->collection = $paginator->getCollection();
            } else {
                # Laravel 5
                $this->collection = Collection::make(
                    $this->getPaginator()->items()
                );
            }

        }
        return $this->collection;
    }

    public function getPaginator()
    {
        if (!$this->paginator) {
            $this->paginator = $this->src->paginate($this->page_size);
        }
        return $this->paginator;
    }

    /**
     * @return \Illuminate\Pagination\Factory
     */
    public function getPaginationFactory()
    {
        return $this->src->getQuery()->getConnection()->getPaginator();
    }

    protected function getIterator()
    {
        if (!$this->iterator) {
            $this->iterator = $this->getCollection()->getIterator();
        }
        return $this->iterator;
    }

    /**
     * @return Builder
     */
    public function getBuilder()
    {
        return $this->src;
    }

    public function getRow()
    {
        if ($this->index < $this->count()) {
            $this->index++;
            $item = $this->iterator->current();
            $this->iterator->next();
            $row = new EloquentDataRow($item, $this->getRowId());
            Event::fire(self::EVENT_FETCH_ROW, [$row, $this]);
            return $row;
        } else {
            return null;
        }
    }

    public function count()
    {
        return $this->getCollection()->count();
    }

    public function orderBy($fieldName, $direction)
    {
        $this->src->orderBy($fieldName, $direction);
        return $this;
    }

    public function filter($fieldName, $operator, $value)
    {
        $this->src->where($fieldName, $operator, $value);
        return $this;
    }

} 