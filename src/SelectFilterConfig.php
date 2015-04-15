<?php
namespace Nayjest\Grids;

class SelectFilterConfig extends FilterConfig
{
    protected $template = '*.select';

    protected $options = [];

    protected $is_submitted_on_change = false;

    /**
     * Returns option items of html select tag.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Sets option items for html select tag.
     *
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Returns true if form must be submitted immediately
     * when filter value selected.
     *
     * @return bool
     */
    public function isSubmittedOnChange()
    {
        return $this->is_submitted_on_change;
    }

    /**
     * Allows to submit form immediately when filter value selected.
     *
     * @param bool $isSubmittedOnChange
     * @return $this
     */
    public function setSubmittedOnChange($isSubmittedOnChange)
    {
        $this->is_submitted_on_change = $isSubmittedOnChange;
        return $this;
    }
}

