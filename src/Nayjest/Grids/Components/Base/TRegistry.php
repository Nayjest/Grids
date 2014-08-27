<?php
namespace Nayjest\Grids\Components\Base;

use Illuminate\Support\Collection;
use Nayjest\Grids\Grid;

trait TRegistry
{
    use TComponent {
        TComponent::initialize as private componentInitialize;
    }

    protected $components;

    /**
     * @return Collection|IComponent[]
     */
    public function getComponents()
    {
        if ($this->components === null) {
            $this->components = new Collection;
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
     * @param string|string[] $tag_names
     *
     * @return Collection|IComponent[]
     */
    public function getTagged($tag_names)
    {
        return $this->getComponents()->filter(
            function (IComponent $component) use ($tag_names) {
                return is_array($tag_names) ? $component->hasTags($tag_names) : $component->hasTag($tag_names);
            }
        );
    }

    public function addComponent(IComponent $component)
    {
        $this->getComponents()->push($component);
        $component->attachTo($this);
        return $this;
    }

    public function setComponents($components)
    {
        $this->components = Collection::make($components);
        return $this;
    }

    public function makeComponent($class_name)
    {
        $component = new $class_name;
        $this->addComponent($component);
        return $component;
    }

    public function initialize(Grid $grid)
    {
        $this->componentInitialize($grid);
        foreach ($this->getComponents() as $component) {
            $component->initialize($grid);
        }
    }

}