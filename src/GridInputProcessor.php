<?php
namespace Nayjest\Grids;

use Input;
use Request;
use Form;

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

    public function __construct(Grid $grid)
    {
        $this->grid = $grid;
        $this->loadInput();
    }

    protected function loadInput()
    {
        $this->input = Input::get($this->getKey(), []);
    }

    public function getInput()
    {
        return $this->input;
    }

    public function getKey()
    {
        return $this->grid->getConfig()->getName();
    }

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

    public function setSorting(FieldConfig $column, $direction)
    {
        $this->input['sort'] = [
            $column->getName() => $direction
        ];
        return $this;
    }

    public function getFilterValue($filterName)
    {
        if (isset($this->input['filters'][$filterName])) {
            return $this->input['filters'][$filterName];
        } else {
            return null;
        }
    }


    public function getValue($key, $default = null)
    {
        if (isset($this->input[$key])) {
            return $this->input[$key];
        } else {
            return $default;
        }
    }

    public function setValue($key, $value)
    {
        $this->input[$key] = $value;
        return $this;
    }

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
            foreach($new_params as $key => $value) {
                $params[$this->getKey()][$key] = $value;
            }
        }
        return http_build_query($params);
    }

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