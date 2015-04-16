<?php
namespace Nayjest\Grids\Components\Base;

use Illuminate\Support\Collection;
use Nayjest\Grids\Grid;

trait TRegistry
{
    protected $components;

    /**
     * Returns default child components.
     *
     * Override this method.
     *
     * @return \Illuminate\Support\Collection|ComponentInterface[]|array
     */
    protected function getDefaultComponents()
    {
        return [];
    }

    /**
     * Returns child components.
     *
     * @return Collection|ComponentInterface[]
     */
    final public function getComponents()
    {
        if ($this->components === null) {
            $this->setComponents($this->getDefaultComponents());
        }
        return $this->components;
    }

    /**
     * Finds child component by name.
     *
     * @param string $name
     * @return null|ComponentInterface
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
     * Finds child component by name recursively.
     *
     * @param string $name
     * @return null|ComponentInterface
     */
    public function getComponentByNameRecursive($name)
    {
        foreach ($this->getComponents() as $component) {
            if ($component->getName() === $name) {
                return $component;
            }
            if ($component instanceof TRegistry || $component instanceof RegistryInterface) {
                if ($res = $component->getComponentByNameRecursive($name)) {
                    return $res;
                }
            }

        }
        return null;
    }

    /**
     * @param string|string[] $tagNames
     * @return Collection|ComponentInterface[]
     */
    public function getTagged($tagNames)
    {
        return $this->getComponents()->filter(
            function (ComponentInterface $component) use ($tagNames) {
                return is_array($tagNames) ? $component->hasTags($tagNames) : $component->hasTag($tagNames);
            }
        );
    }

    /**
     * Adds component to the collection of child components.
     *
     * @param ComponentInterface $component
     * @return $this
     */
    public function addComponent(ComponentInterface $component)
    {
        $this->getComponents()->push($component);
        $component->attachTo($this);
        return $this;
    }

    /**
     * Allows to specify collection of child components.
     *
     * @param \Illuminate\Support\Collection|ComponentInterface[]|array $components
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
     * Adds set of components to the collection of child components.
     *
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

    /**
     * Creates component,
     * adds it to child components collection and returns it.
     *
     * @param string $class
     * @return ComponentInterface
     */
    public function makeComponent($class)
    {
        $component = new $class;
        $this->addComponent($component);
        return $component;
    }

    /**
     * Initializes child components.
     *
     * @param Grid $grid
     */
    public function initializeComponents(Grid $grid)
    {
        foreach ($this->getComponents() as $component) {
            $component->initialize($grid);
        }
    }

    /**
     * Prepares child components for rendering.
     */
    public function prepareComponents()
    {
        foreach ($this->getComponents() as $component) {
            $component->prepare();
        }
    }
}
