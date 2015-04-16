<?php
namespace Nayjest\Grids;

use Input;
use Request;
use Form;

/**
 * Class GridInputProcessor
 *
 * This class manages input processing for grid.
 *
 * @package Nayjest\Grids
 */
class GridInputProcessor
{
    /**
     * @var Grid
     */
    protected $grid;

    /**
     * @var array
     */
    protected $input;

    /**
     * Constructor.
     *
     * @param Grid $grid
     */
    public function __construct(Grid $grid)
    {
        $this->grid = $grid;
        $this->loadInput();
    }

    protected function loadInput()
    {
        $this->input = Input::get($this->getKey(), []);
    }

    /**
     * Returns input related to grid.
     *
     * @return array
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * Returns input key for grid parameters.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->grid->getConfig()->getName();
    }

    /**
     * Returns sorting parameters passed to input.
     *
     * @return mixed
     */
    public function getSorting()
    {
        return $_ =& $this->input['sort'];
    }

    public function getSortingHiddenInputsHtml()
    {
        $html = '';

        $key = $this->getKey();
        if (isset($this->input['sort'])) {
            foreach ($this->input['sort'] as $field => $direction) {
                $html .= Form::hidden("{$key}[sort][$field]", $direction);
            }
        }
        return $html;
    }

    /**
     * Returns UID for current grid state.
     *
     * Currently used as key for caching.
     *
     * @return string
     */
    public function getUniqueRequestId()
    {
        $cookies_str = '';
        foreach ($_COOKIE as $key => $val) {
            if (strpos($key, $this->getKey()) !== false) {
                $cookies_str .= $key . json_encode($val);
            }
        }

        return md5($cookies_str . $this->getKey() . json_encode($this->getInput()));
    }

    /**
     * @param FieldConfig $column
     * @param $direction
     * @return $this
     */
    public function setSorting(FieldConfig $column, $direction)
    {
        $this->input['sort'] = [
            $column->getName() => $direction
        ];
        return $this;
    }

    /**
     * Returns input value for filter.
     *
     * @param string $filterName
     * @return mixed
     */
    public function getFilterValue($filterName)
    {
        if (isset($this->input['filters'][$filterName])) {
            return $this->input['filters'][$filterName];
        } else {
            return null;
        }
    }

    /**
     * Returns value of input parameter related to grid.
     *
     * @param string $key
     * @param $default
     * @return mixed
     */
    public function getValue($key, $default = null)
    {
        if (isset($this->input[$key])) {
            return $this->input[$key];
        } else {
            return $default;
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function setValue($key, $value)
    {
        $this->input[$key] = $value;
        return $this;
    }

    /**
     * Returns current query string extended by specified GET parameters.
     *
     * @param array $new_params
     * @return string
     */
    public function getQueryString(array $new_params = [])
    {
        $params = $_GET;
        if (!empty($this->input)) {
            $params[$this->getKey()] = $this->input;
        }
        if (!empty($new_params)) {
            if (empty($params[$this->getKey()])) {
                $params[$this->getKey()] = [];
            }
            foreach ($new_params as $key => $value) {
                $params[$this->getKey()][$key] = $value;
            }
        }
        return http_build_query($params);
    }

    /**
     * Returns current URL extended by specified GET parameters.
     *
     * @param array $new_params
     * @return string
     */
    public function getUrl(array $new_params = [])
    {
        if (null !== $query_string = $this->getQueryString($new_params)) {
            $query_string = '?' . $query_string;
        }
        $request = Request::instance();
        return $request->getSchemeAndHttpHost()
        . $request->getBaseUrl()
        . $request->getPathInfo()
        . $query_string;
    }
}
