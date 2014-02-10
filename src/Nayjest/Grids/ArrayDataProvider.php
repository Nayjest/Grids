<?php
namespace Nayjest\Grids;

use Nayjest\Grids\Filters\FilterInterface;

class ArrayDataProvider extends AbstractDataProvider implements DataProviderInterface
{
    protected $src;

    public function __construct(array $src = [])
    {
        $this->src = array_values($src);
    }

    /**
     * @param int $offset
     * @param int $limit
     * @return \Generator
     */
    public function fetch($offset = 0, $limit = null)
    {
        if ($this->sorter !== null) {
            $this->sorter->sort($this->src);
        }
        /** @var $filter FilterInterface */
        foreach ($this->filters as $filter) {
            $filter->filter($this->src);
        }
        # $len = count($this->src);
        # $last = $limit ? min($offset + $limit, $len) : $len;
        # @todo use yield
        # for ($i = $offset; $i < $last; $i++) {
        #    yield $this->src[$i];
        # }
        return array_slice($this->src, $offset, $limit ? : null);
    }

    public function getRecordsCount()
    {
        return count($this->src);
    }

} 