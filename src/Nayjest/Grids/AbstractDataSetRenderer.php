<?php
namespace Nayjest\Grids;

use Nayjest\Common\Widgets\WithView;
use \Exception;

abstract class AbstractDataSetRenderer implements DataSetRendererInterface
{
    use WithPagination;

    /**
     * @var DataProviderInterface[]
     */
    protected $providers = [];

    public function addProvider(DataProviderInterface $provider, $name = null)
    {
        if ($name) {
            if (isset($this->providers[$name])) {
                throw new Exception("Provider with specified name '$name' already exists");
            }
            $this->providers['name'] = $provider;
        } else {
            $this->providers[] = $provider;
        }
    }

    public function getProvider($name = null) {
        if (!$name) {
            return $this->providers[0];
        } else {
            return $this->providers[$name];
        }
    }

    public function getViewData() {

        foreach($this->providers as $provider) {

        }
    }

    abstract public function render();

} 