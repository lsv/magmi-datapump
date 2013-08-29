<?php
/**
 * Created by lsv
 * Date: 8/29/13
 * Time: 2:39 AM
 */

namespace Datapump\Product;

use Datapump\Exception;

class Configurable extends ProductAbstract
{

	const CONFIG_ATTR_KEY = 'configurable_attributes';

	protected $type = self::TYPE_CONFIGURABLE;

	protected $simpleProducts = array();

	protected $requiredData = array(
		'Type'				=> 'Missing product type',
		'Sku' 				=> 'Missing SKU number',
		'Visibility'		=> 'Missing visibility status',
		'Description' 		=> 'Missing description',
		'ShortDescription' 	=> 'Missing short description',
		'Name'				=> 'Missing product name',
		'Status'			=> 'Missing product status',
		'Price'				=> 'Missing product price',
	);

	/**
	 * @param string $sku
	 * @param string|array $configurableAttribute
	 */
	public function __construct($sku, $configurableAttribute)
	{
		if (!is_array($configurableAttribute)) {
			$configurableAttribute = array($configurableAttribute);
		}

		$this->set(self::CONFIG_ATTR_KEY, $configurableAttribute);
		parent::__construct($sku);
	}

	public function addSimpleProduct(Simple $product, $visibleInFrontend = false, $searchable = false)
	{
		if (! $this->simpleProductConfigAttributeTest($product)) {
			throw new Exception\SimpleProductMissingConfigurableAttribute($product->getSku() . ' is missing the keys ' . implode(', ', $this->get(self::CONFIG_ATTR_KEY)));
		}

		foreach($this->simpleProducts AS $p) {
			/** @var Simple $p */
			if ($p->getSku() == $product->getSku()) {
				throw new Exception\ProductSkuAlreadyAdded('Product with SKU: ' . $product->getSku() . ' is already added');
			}
		}

		$product->setVisibility($visibleInFrontend, $searchable);
		$this->simpleProducts[] = $product;
		return $this;
	}

	public function getSimpleProduct($sku)
	{
		foreach($this->simpleProducts AS $product) {
			/** @var Simple $product */
			if ($product->getSku() == $sku) {
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
		return $this->data[self::CONFIG_ATTR_KEY];
	}

	protected function setConfigPrice()
	{
		$price = 0;
		foreach($this->simpleProducts AS $p) {
			/** @var Simple $p */
			if ($p->getPrice() > $price) {
				$price = $p->getPrice();
			}
		}

		$this->setPrice($price);
	}

	public function beforeAddingToHolder()
	{
		$this->setConfigPrice();
	}

	public function beforeImport()
	{
		$products = array();
		foreach($this->simpleProducts AS $product) {
			/** @var Simple $product */
			$products[] = $product->getSku();
		}

		$this->set('simple_skus', implode(',', $products));
	}

	protected function checkRequired()
	{
	}

	private function simpleProductConfigAttributeTest(Simple $product)
	{
		foreach($this->get(self::CONFIG_ATTR_KEY) AS $key) {
			if (! $product->_isset($key)) return false;
		}

		return true;

	}

}