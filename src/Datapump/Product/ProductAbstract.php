<?php
/**
 * Created by lsv
 * Date: 8/29/13
 * Time: 2:32 AM
 */

namespace Datapump\Product;

use Datapump\Product\Data\RequiredData;
use Datapump\Product\Data\DataInterface;
use Datapump\Exception;

abstract class ProductAbstract
{

	protected $type = '';

	protected $requiredFields = array();

	/**
	 * @var RequiredData
	 */
	protected $requiredData;

	private $data = array();

	public function __construct(RequiredData $data)
	{
		$data->setType($this->type);
		$this->injectData($data);
	}

	public function injectData(DataInterface $data)
	{
		if ($data instanceof RequiredData) {
			$this->requiredData = $data;
			return $this;
		}

		$this->data = array_merge($this->data, $data->getData());
		return $this;
	}

	public function getRequiredData()
	{
		return $this->requiredData;
	}

	public function set($key, $value)
	{
		$this->getRequiredData()->set($key, $value);
		return $this;
	}

	public function __set($key, $value)
	{
		return $this->set($key, $value);
	}

	public function get($key)
	{
		return $this->getRequiredData()->get($key);
	}

	public function __get($key)
	{
		return $this->get($key);
	}

	public function check()
	{
		$missingFields = array();

		foreach($this->requiredFields AS $key => $msg) {
			$method = 'get' . ucfirst($key);

			if (! $this->getRequiredData()->{$method}()) {
				$missingFields[] = $msg;
			}
		}

		if ($missingFields) {
			throw new Exception\MissingProductData('Product with SKU: "' . $this->getRequiredData()->getSku() . '" does not have the all the required data' . "\n" . implode("\n", $missingFields));
		}

		return true;

	}

	public function beforeImport()
	{
		$this->data = array_merge($this->data, $this->requiredData->getData());
		return $this;
	}

	public function afterImport()
	{
	}

	public function getData()
	{
		$this->beforeImport();
		return $this->data;
	}

}