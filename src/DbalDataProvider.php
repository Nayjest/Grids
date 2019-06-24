<?php
namespace Nayjest\Grids;

use DB;
use Doctrine\DBAL\Query\QueryBuilder;
use Event;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;

class DbalDataProvider extends DataProvider
{
    protected $collection;

    protected $paginator;

    /** @var  $iterator \ArrayIterator */
    protected $iterator;

    /**
     * Set true if Laravel query logging required.
     * Fails when using Connection::PARAM_INT_ARRAY parameters
     * @var bool
     */
    protected $exec_using_laravel = false;

    /**
     * Constructor.
     *
     * @param QueryBuilder $src
     */
    public function __construct(QueryBuilder $src)
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
            $query = clone $this->src;
            $query
                ->setFirstResult(
                    ($this->getCurrentPage() - 1) * $this->page_size
                )
                ->setMaxResults($this->page_size);
            if ($this->isExecUsingLaravel()) {
                $res = DB::select($query, $query->getParameters());
            } else {
                $res = $query->execute()->fetchAll(\PDO::FETCH_OBJ);
            }
            $this->collection = Collection::make($res);
        }
        return $this->collection;
    }


    public function getPaginator()
    {
        if (!$this->paginator) {
            $items = $this->getCollection()->toArray();
            if (version_compare(Application::VERSION, '5.0.0', '<')) {
                $this->paginator = \Paginator::make(
                    $items,
                    $this->getTotalRowsCount(),
                    $this->page_size
                );
            } else {
                $this->paginator = new \Illuminate\Pagination\LengthAwarePaginator(
                    $items,
                    $this->getTotalRowsCount(),
                    $this->page_size,
                    $this->getCurrentPage(),
                    [
                        'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()
                    ]
                );
            }
        }
        return $this->paginator;
    }

    /**
     * @return \Illuminate\Pagination\Factory
     */
    public function getPaginationFactory()
    {
        return \App::make('paginator');
    }

    protected function getIterator()
    {
        if (!$this->iterator) {
            $this->iterator = $this->getCollection()->getIterator();
        }
        return $this->iterator;
    }

    /**
     * @return QueryBuilder
     */
    public function getBuilder()
    {
        return $this->src;
    }

    public function getRow()
    {
        if ($this->index < $this->getCurrentPageRowsCount()) {
            $this->index++;
            $item = $this->iterator->current();
            $this->iterator->next();
            $row = new ObjectDataRow($item, $this->getRowId());
            Event::dispatch(self::EVENT_FETCH_ROW, [$row, $this]);
            return $row;
        } else {
            return null;
        }
    }

    protected $_count;

    /**
     * @deprecated
     * @return int
     */
    public function count()
    {
        return $this->getCurrentPageRowsCount();
    }

    public function getTotalRowsCount()
    {
        return $this->src->execute()->rowCount();
    }

    public function getCurrentPageRowsCount()
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
                // may be broken, @see https://github.com/Nayjest/Grids/issues/109
                $operator = 'IN';
                if (!is_array($value)) {
                    $operator = '=';
                }
                break;
        }
        $parameterName = str_replace(".", "_", $fieldName); // @see https://github.com/Nayjest/Grids/issues/111
        $this->src->andWhere("$fieldName $operator :$parameterName");
        $this->src->setParameter($parameterName, $value);
        return $this;
    }

    /**
     * @return boolean
     */
    public function isExecUsingLaravel()
    {
        return $this->exec_using_laravel;
    }

    /**
     * @param boolean $execUsingLaravel
     */
    public function setExecUsingLaravel($execUsingLaravel)
    {
        $this->exec_using_laravel = $execUsingLaravel;
    }

}
