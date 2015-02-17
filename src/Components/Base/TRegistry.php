<?php
namespace Nayjest\Grids\Components\Base;

use Illuminate\Support\Collection;
use Nayjest\Grids\Grid;

trait TRegistry
{
    protected $components;

    /**
     * Components in registry by default
     *
     * Override this method
     * @return array
     */
    protected function getDefaultComponents()
    {
        return [];
    }

    /**
     * @return Collection|IComponent[]
     */
    final public function getComponents()
    {
        if ($this->components === null) {
            $this->setComponents($this->getDefaultComponents());
        }
        return $this->components;
    }

    /**
     * @param string $name
     * @return null|IComponent
     */
    public function getComponentByName($name)
    {
        foreach ($this->getComponents() as $component) {
            if ($component->getName() === $name) {
                return $component;
            }
        }
    }

    /**
     * @param string $name
     * @return null|IComponent
     */
    public function getComponentByNameRecursive($name)
    {
        foreach ($this->getComponents() as $component) {
            if ($component->getName() === $name) {
                return $component;
            }
            if ($component instanceof TRegistry || $component instanceof IRegistry) {
                if ($res = $component->getComponentByNameRecursive($name)) {
                    return $res;
                }
            }

        }
        return null;
    }

    /**
     * @param string|string[] $tagNames
     *
     * @return Collection|IComponent[]
     */
    public function getTagged($tagNames)
    {
        return $this->getComponents()->filter(
            function (IComponent $component) use ($tagNames) {
                return is_array($tagNames) ? $component->hasTags($tagNames) : $component->hasTag($tagNames);
            }
        );
    }

    public function addComponent(IComponent $component)
    {
        $this->getComponents()->push($component);
        $component->attachTo($this);
        return $this;
    }

    /**
     * @param \Illuminate\Support\Collection|IComponent[]|array $components
     * @return $this
     */
    public function setComponents($components)
    {
        $this->components = Collection::make($components);
        foreach ($components as $component) {
            $component->attachTo($this);
        }
        return $this;
    }

    /**
     * @param  Collection|\Illuminate\Support\Contracts\ArrayableInterface|array  $components
     * @return $this
     */
    public function addComponents($components)
    {
        $this->setComponents(
            $this->getComponents()->merge($components)
        );
        return $this;
    }

    public function makeComponent($class)
    {
        $component = new $class;
        $this->addComponent($component);
        return $component;
    }

    public function initializeComponents(Grid $grid)
    {
        foreach ($this->getComponents() as $component) {
            $component->initialize($grid);
        }
    }

    public function prepareComponents()
    {
        foreach ($this->getComponents() as $component) {
            $component->prepare();
        }
    }

}