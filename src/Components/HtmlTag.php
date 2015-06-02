<?php
namespace Nayjest\Grids\Components;

use Nayjest\Grids\Components\Base\RenderableRegistry;

class HtmlTag extends RenderableRegistry
{
    protected $tag_name;

    protected $content;

    /**
     * HTML tag attributes.
     * Keys are attribute names and values are attribute values.
     * @var array
     */
    protected $attributes = [];

    /**
     * Returns component name.
     * If empty, tag_name will be used instead
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name ?: $this->getTagName();
    }

    /**
     * Allows to specify HTML tag.
     *
     * @param string $name
     * @return $this
     */
    public function setTagName($name)
    {
        $this->tag_name = $name;
        return $this;
    }

    /**
     * Returns HTML tag.
     *
     * @return string
     */
    public function getTagName()
    {
        return $this->tag_name ?: $this->suggestTagName();
    }

    /**
     * Suggests tag name by class name.
     *
     * @return string
     */
    private function suggestTagName()
    {
        $class_name = get_class($this);
        $parts = explode('\\', $class_name);
        $base_name = array_pop($parts);
        return ($base_name === 'HtmlTag') ? 'div' : strtolower($base_name);
    }

    /**
     * Sets content (html inside tag).
     *
     * @param string $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Returns html inside tag.
     *
     * @return string|null
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Sets html tag attributes.
     * Keys are attribute names and values are attribute values.
     *
     * @param array $attributes
     * @return $this
     */
    public function setAttributes(array $attributes = [])
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * Returns html tag attributes.
     * Keys are attribute names and values are attribute values.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Renders opening tag.
     *
     * @return string
     */
    public function renderOpeningTag()
    {
        /** @var \Collective\Html\HtmlBuilder $html */
        $html = app('html');
        return '<'
        . $this->getTagName()
        . $html->attributes($this->getAttributes())
        . '>';
    }

    /**
     * Renders closing tag.
     *
     * @return string
     */
    public function renderClosingTag()
    {
        return "</{$this->getTagName()}>";
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        if ($this->getTemplate()) {
            $inner = $this->renderTemplate();
        } else {
            $this->is_rendered = true;
            $inner = $this->renderOpeningTag()
                . $this->renderComponents(self::SECTION_BEGIN)
                . $this->getContent()
                . $this->renderComponents(null)
                . $this->renderComponents(self::SECTION_END)
                . $this->renderClosingTag();
        }
        return $this->wrapWithOutsideComponents($inner);
    }
}

