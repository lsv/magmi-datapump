<?php
/**
 * Created by lsv
 * Date: 8/29/13
 * Time: 2:35 AM
 */

namespace Datapump\Product;

use Datapump\Exception;
use Datapump\Logger\Logger;

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

	public function addProduct(ProductAbstract $product)
	{

		$product->beforeAddingToHolder();

		if (($missingdata = $product->checkMissingData()) !== true) {
			throw new Exception\MissingProductData('Product does not have the all the required data' . "\n" . implode("\n", $missingdata));
		}

		foreach($this->products AS $p) {
			/** @var ProductAbstract $p */
			if ($p->getSku() == $product->getSku()) {
				throw new Exception\ProductSkuAlreadyAdded('Product with SKU: ' . $product->getSku() . ' is already added');
			}
		}

		if ($product instanceof Simple) {
			$this->products[] = $product;
			return $this;
		}

		if ($product instanceof Configurable) {
			$this->products[] = $product;
			return $this;
		}

		throw new Exception\MissingProductType(get_class($product) . ' does not implement any known product type');

	}

	public function removeProduct($sku)
	{
		foreach($this->products AS $key => $product) {
			/** @var ProductAbstract $product */
			if ($product->getSku() == $sku) {
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
			if ($product->getSku() == $sku) {
				return $product;
			}
		}

		return false;

	}

	public function import()
	{
		foreach($this->products AS $product) {
			/** @var ProductAbstract $product */
			switch ($product->getType()) {
				case ProductAbstract::TYPE_CONFIGURABLE:
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
		$this->magmi->ingest($product->toArray());
		$product->afterImport();
	}

}