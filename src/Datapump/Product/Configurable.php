<?php
/**
 * Created by lsv
 * Date: 8/29/13
 * Time: 2:39 AM
 */

namespace Datapump\Product;

use Datapump\Exception;
use Datapump\Product\Data\DataInterface;
use Datapump\Product\Data\RequiredData;

class Configurable extends ProductAbstract
{

	const CONFIG_ATTR_KEY = 'configurable_attributes';

	protected $type = DataInterface::TYPE_CONFIGURABLE;

	protected $simpleProducts = array();

	protected $requiredFields = array(
		'Type'				=> 'Missing product type',
		'Sku' 				=> 'Missing SKU number',
		'Visibility'		=> 'Missing visibility status',
		'Description' 		=> 'Missing description',
		'ShortDescription' 	=> 'Missing short description',
		'Name'				=> 'Missing product name',
		'Status'			=> 'Missing product status',
		'Price'				=> 'Missing product price',
	);

	public function __construct(RequiredData $data, $configurableAttribute)
	{
		if (!is_array($configurableAttribute)) {
			$configurableAttribute = array($configurableAttribute);
		}

		$data->set(self::CONFIG_ATTR_KEY, $configurableAttribute);
		parent::__construct($data);
	}

	public function addSimpleProduct(Simple $product, $visibleInFrontend = false, $searchable = false)
	{
		$product->check();

		if (! $this->simpleProductConfigAttributeTest($product)) {
			throw new Exception\SimpleProductMissingConfigurableAttribute($product->getRequiredData()->getSku() . ' is missing the keys ' . implode(', ', $this->getRequiredData()->get(self::CONFIG_ATTR_KEY)));
		}

		foreach($this->simpleProducts AS $p) {
			/** @var Simple $p */
			if ($p->get('sku') === $product->get('sku')) {
				throw new Exception\ProductSkuAlreadyAdded('Product with SKU: ' . $product->get('sku') . ' is already added');
			}
		}

		$product->getRequiredData()->setVisibility($visibleInFrontend, $searchable);
		$this->simpleProducts[] = $product;
		return $this;
	}

	public function getSimpleProduct($sku)
	{
		foreach($this->simpleProducts AS $product) {
			/** @var Simple $product */
			if ($product->getRequiredData()->getSku() == $sku) {
				return $product;
			}
		}

		return null;

	}

	public function getSimpleProducts()
	{
		return $this->simpleProducts;
	}

	public function countSimpleProducts()
	{
		return count($this->getSimpleProducts());
	}

	public function getConfigurableAttribute()
	{
		return $this->getRequiredData()->get(self::CONFIG_ATTR_KEY);
	}

	public function beforeImport()
	{
		$this->setConfigPrice();
		parent::beforeImport();
	}

	protected function setConfigPrice()
	{
		$price = 0;
		foreach($this->simpleProducts AS $p) {
			/** @var Simple $p */
			if ($p->getRequiredData()->getPrice() > $price) {
				$price = $p->getRequiredData()->getPrice();
			}
		}

		$this->getRequiredData()->setPrice($price);
	}

	private function simpleProductConfigAttributeTest(Simple $product)
	{
		foreach($this->get(self::CONFIG_ATTR_KEY) AS $key) {
			if ($product->get($key) === null) {
				return false;
			}
		}

		return true;
	}

}