<?php
/**
 * Created by lsv
 * Date: 8/29/13
 * Time: 2:35 AM
 */

namespace Datapump\Product;

use Datapump\Exception;
use Datapump\Logger\Logger;
use Datapump\Product\Data\DataAbstract;

class ItemHolder
{

	const MAGMI_CREATE = 'create';
	const MAGMI_UPDATE = 'update';
	const MAGMI_XCREATE = 'xcreate';

	private $products = array();

	/**
	 * @var \Magmi_ProductImport_DataPump
	 */
	private $magmi;

	public function setMagmi(\Magmi_ProductImport_DataPump $magmi, $profile, $mode = self::MAGMI_CREATE, Logger $logger)
	{
		$this->magmi = $magmi;
		$this->magmi->beginImportSession($profile, $mode, $logger);
	}

	/**
	 * @param ProductAbstract|array $product
	 *
	 * @return $this
	 * @throws \Datapump\Exception\ProductSkuAlreadyAdded
	 * @throws \Datapump\Exception\ProductNotAnArrayOrProductAbstract
	 */
	public function addProduct($product)
	{
		if (is_array($product)) {
			foreach($product AS $p) {
				$this->addProduct($p);
			}
			return $this;
		}

		if ($product instanceof ProductAbstract) {
			$check = $product->check();
			if ($check) {
				$sku = $product->get('sku');
				foreach($this->products AS $p) {
					/** @var ProductAbstract $p */
					if ($p->get('sku') == $sku) {
						throw new Exception\ProductSkuAlreadyAdded('Product with SKU: ' . $product->get('sku') . ' is already added');
					}
				}
				$this->products[] = $product;
			}

			return $this;
		}

		throw new Exception\ProductNotAnArrayOrProductAbstract(get_class($product) . ' is not valid');

	}

	public function removeProduct($sku)
	{
		foreach($this->products AS $key => $product) {
			/** @var ProductAbstract $product */
			if ($product->getRequiredData()->getSku() == $sku) {
				unset($this->products[$key]);
				return true;
			}
		}

		return false;

	}

	public function findProduct($sku)
	{
		foreach($this->products AS $product) {
			/** @var ProductAbstract $product */
			if ($product->getRequiredData()->getSku() == $sku) {
				return $product;
			}
		}

		return false;

	}

	/**
	 * @todo rewrite this, so it can be overwritten
	 */
	public function import()
	{
		foreach($this->products AS $product) {
			/** @var ProductAbstract $product */
			switch ($product->getRequiredData()->getType()) {
				case DataAbstract::TYPE_CONFIGURABLE:
					/** @var Configurable $product */
					foreach($product->getSimpleProducts() AS $simple) {
						/** @var Simple $simple */
						$this->ingest($simple);
					}
					$this->ingest($product);
					break;
				default:
					$this->ingest($product);
					break;
			}
		}

		$this->magmi->endImportSession();
	}

	private function ingest(ProductAbstract $product)
	{
		$product->beforeImport();
		$this->magmi->ingest($product->getData());
		$product->afterImport();
	}

}