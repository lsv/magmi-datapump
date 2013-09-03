<?php
/**
 * Created by lsv
 * Date: 8/30/13
 * Time: 12:55 PM
 */

namespace Datapump\Product\Data;


class RequiredData
	extends DataAbstract
{

	protected $data = array(
		'attribute_set' => 'Default',
		'type' => DataInterface::TYPE_SIMPLE,
		'visibility' => DataInterface::VISIBILITY_CATALOG_SEARCH,
		'weight' => 0.01,
		'status' => 1,
		'store' => 'admin'
	);

	public function setStore($store)
	{
		$this->set('store', $store);
		return $this;
	}

	public function getStore()
	{
		return $this->get('store');
	}


	public function setAttributeSet($set)
	{
		$this->set('attribute_set', $set);
		return $this;
	}

	public function getAttributeSet()
	{
		return $this->get('attribute_set');
	}


	public function setType($type)
	{
		$this->set('type', $type);
		return $this;
	}

	public function getType()
	{
		return $this->get('type');
	}

	/**
	 * @param string $sku
	 * @return RequiredData
	 */
	public function setSku($sku)
	{
		$this->set('sku', $sku);
		return $this;
	}

	/**
	 * @return null|string
	 */
	public function getSku()
	{
		return $this->get('sku');
	}


	public function setVisibility($visibleInCatalog = true, $searchable = true)
	{
		$vis = self::VISIBILITY_NOTVISIBLE;
		if ($visibleInCatalog && $searchable) {
			$vis = self::VISIBILITY_CATALOG_SEARCH;
		} elseif ($visibleInCatalog) {
			$vis = self::VISIBILITY_CATALOG;
		} elseif ($searchable) {
			$vis = self::VISIBILITY_SEARCH;
		}

		$this->set('visibility', $vis);
		return $this;
	}

	public function getVisibility()
	{
		return $this->get('visibility');
	}


	public function setDescription($description)
	{
		$this->set('description', $description);
		return $this;
	}

	public function getDescription()
	{
		return $this->get('description');
	}


	public function setShortDescription($description)
	{
		$this->set('short_description', $description);
		return $this;
	}

	public function getShortDescription()
	{
		return $this->get('short_description');
	}


	public function setName($name)
	{
		$this->set('name', $name);
		return $this;
	}

	public function getName()
	{
		return $this->get('name');
	}


	public function setWeight($weight)
	{
		$this->set('weight', (float)$weight);
		return $this;
	}

	public function getWeight()
	{
		return $this->get('weight');
	}


	public function setStatus($enabled)
	{
		$this->set('status', ($enabled ? 1 : 2));
		return $this;
	}

	public function getStatus()
	{
		$status = $this->get('status');
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
		$this->set('price', (float)$price);
		return $this;
	}

	public function getPrice()
	{
		return $this->get('price');
	}


	public function setTax($tax)
	{
		$this->set('tax_class_id', (int)$tax);
		return $this;
	}

	public function getTax()
	{
		return $this->get('tax_class_id');
	}


	public function setQty($qty)
	{
		if ($qty === null) {
			$this->set('manage_stock', 0);
			$this->set('is_in_stock', 1);
			$this->set('qty', (int)0);
			return $this;
		} else {
			$this->set('manage_stock', 1);
		}

		if ($qty <= 0) {
			$this->set('is_in_stock', 0);
		} else {
			$this->set('is_in_stock', 1);
		}

		$this->set('qty', (int)$qty);
		return $this;
	}

	public function getQty()
	{
		return $this->get('qty');
	}

	public function getData()
	{
		return $this->data;
	}

}