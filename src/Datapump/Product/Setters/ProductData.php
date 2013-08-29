<?php
/**
 * Created by lsv
 * Date: 8/29/13
 * Time: 6:34 PM
 */

namespace Datapump\Product\Setters;


class ProductData
	extends RequiredData
	implements SetterInterface
{

	protected $data;

	public function __set($key, $value)
	{
		$this->_setData($key, $value);
		return $this;
	}

	public function __get($key)
	{
		return $this->_getData($key);
	}

	public function set($key, $value)
	{
		$this->_setData($key, $value);
		return $this;
	}

	public function get($key)
	{
		return $this->_getData($key);
	}

	protected function _isset($key)
	{
		return isset($this->data[$key]);
	}

	/**
	 * @param string $key
	 * @return null|string
	 */
	protected function _getData($key)
	{
		if ($this->_isset($key)) {
			return $this->data[$key];
		}

		return null;
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 *
	 * @return $this
	 */
	protected function _setData($key, $value)
	{
		$this->data[$key] = $value;
		return $this;
	}

}