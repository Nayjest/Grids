<?php
namespace Nayjest\Grids;

use Illuminate\Database\Eloquent\Builder;
use Event;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;

class EloquentDataProvider extends DataProvider
{
    protected $collection;

    protected $paginator;

    /** @var  $iterator \ArrayIterator */
    protected $iterator;

    /**
     * Constructor.
     *
     * @param Builder $src
     */
    public function __construct(Builder $src)
    {
        parent::__construct($src);
    }

    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        $this->getIterator()->rewind();
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCollection()
    {
        if (!$this->collection) {
            $paginator = $this->getPaginator();
            if (version_compare(Application::VERSION, '5', '<')) {
                $this->collection = $paginator->getCollection();
            } else {
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

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->getCollection()->count();
    }

    /**
     * {@inheritdoc}
     */
    public function orderBy($fieldName, $direction)
    {
        $this->src->orderBy($fieldName, $direction);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function filter($fieldName, $operator, $value)
    {
        switch ($operator) {
            case "eq":
                $operator = '=';
                break;
            case "n_eq":
                $operator = '<>';    
                break;
            case "gt":
                $operator = '>';    
                 break;
            case "lt":
                $operator = '<';    
                break;
            case "ls_e":
                $operator = '<=';    
                break;
            case "gt_e":
                $operator = '>=';    
                break;
            case "in":
                if (!is_array($value)) {
                    $operator = '=';
                    break;
                }
                $this->src->whereIn($fieldName, $value);
                return $this;
        }
        $this->src->where($fieldName, $operator, $value);
        return $this;
    }
}
