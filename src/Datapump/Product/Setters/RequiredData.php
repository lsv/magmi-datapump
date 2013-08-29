<?php
/**
 * Created by lsv
 * Date: 8/29/13
 * Time: 6:35 PM
 */

namespace Datapump\Product\Setters;

use Datapump\Product\ProductAbstract;

class RequiredData
	extends Category
	implements SetterInterface
{

	protected function setType($type)
	{
		$this->_setData('type', $type);
		return $this;
	}

	public function getType()
	{
		return $this->_getData('type');
	}


	public function setSku($sku)
	{
		$this->_setData('sku', $sku);
		return $this;
	}

	/**
	 * @return null|string
	 */
	public function getSku()
	{
		return $this->_getData('sku');
	}


	public function setVisibility($visibleInCatalog = true, $searchable = true)
	{
		if ($visibleInCatalog && $searchable) {
			$this->_setData('visibility', ProductAbstract::VISIBILITY_CATALOG_SEARCH);
			return $this;
		}

		if ($visibleInCatalog) {
			$this->_setData('visibility', ProductAbstract::VISIBILITY_CATALOG);
			return $this;
		}

		if ($searchable) {
			$this->_setData('visibility', ProductAbstract::VISIBILITY_SEARCH);
			return $this;
		}

		$this->_setData('visibility', ProductAbstract::VISIBILITY_NOTVISIBLE);
		return $this;
	}

	public function getVisibility()
	{
		return $this->_getData('visibility');
	}


	public function setDescription($description)
	{
		$this->_setData('description', $description);
		return $this;
	}

	public function getDescription()
	{
		return $this->_getData('description');
	}


	public function setShortDescription($description)
	{
		$this->_setData('short_description', $description);
		return $this;
	}

	public function getShortDescription()
	{
		return $this->_getData('short_description');
	}


	public function setName($name)
	{
		$this->_setData('name', $name);
		return $this;
	}

	public function getName()
	{
		return $this->_getData('name');
	}


	public function setWeight($weight)
	{
		$this->_setData('weight', (float)$weight);
		return $this;
	}

	public function getWeight()
	{
		return $this->_getData('weight');
	}


	public function setStatus($enabled)
	{
		$this->_setData('status', ($enabled ? 1 : 2));
		return $this;
	}

	public function getStatus()
	{
		$status = $this->_getData('status');
		//return ($status === 1 ? true : false);
		return $status;
	}

	public function setEnabled()
	{
		$this->setStatus(true);
		return $this;
	}

	public function setDisabled()
	{
		$this->setStatus(false);
		return $this;
	}


	public function setPrice($price)
	{
		$this->_setData('price', (float)$price);
		return $this;
	}

	public function getPrice()
	{
		return $this->_getData('price');
	}


	public function setTax($tax)
	{
		$this->_setData('tax_class_id', (int)$tax);
		return $this;
	}

	public function getTax()
	{
		return $this->_getData('tax_class_id');
	}


	public function setQty($qty)
	{
		if ($qty === null) {
			$this->_setData('manage_stock', 0);
			$this->_setData('is_in_stock', 1);
			$this->_setData('qty', (int)0);
			return $this;
		} else {
			$this->_setData('manage_stock', 1);
		}

		if ($qty <= 0) {
			$this->_setData('is_in_stock', 0);
		} else {
			$this->_setData('is_in_stock', 1);
		}

		$this->_setData('qty', (int)$qty);
		return $this;
	}

	public function getQty()
	{
		return $this->_getData('qty');
	}

	public function getData()
	{
		return $this->data;
	}

}