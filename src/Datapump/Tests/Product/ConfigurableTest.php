<?php
/**
 * Created by lsv
 * Date: 8/29/13
 * Time: 4:24 PM
 */

namespace Datapump\Tests;

use Datapump\Product\ItemHolder;
use Datapump\Product\Simple;
use Datapump\Product\Configurable;

class ConfigurableTest extends \PHPUnit_Framework_TestCase
{

	protected $productHolder;
	protected $simpleProduct;

	public function __construct()
	{
		$this->productHolder = new ItemHolder;

		$this->simpleProduct = new Simple('sku');
		$this->simpleProduct->setDescription('Description')
			->setName('name')
			->setPrice(100)
			->setQty(100)
			->setShortDescription('short description')
			->setTax(1)
			->setWeight(100);

		parent::__construct();
	}

	public function test_SimpleProductMissingKey()
	{
		$this->setExpectedException('Datapump\Exception\SimpleProductMissingConfigurableAttribute');
		$product = new Configurable('config-sku', 'color');
		$product->addSimpleProduct($this->simpleProduct);
	}

	public function test_SimpleProductHasKey()
	{
		$product = new Configurable('config-sku', 'color');

		$simpleproduct1 = clone $this->simpleProduct;
		$simpleproduct1->set('color', 'blue');
		$product->addSimpleProduct($simpleproduct1);

		$this->assertEquals(1, $product->countSimpleProducts());

		$simpleproduct2 = clone $this->simpleProduct;
		$simpleproduct2->setSku('sku2');
		$simpleproduct2->set('color', 'green');
		$product->addSimpleProduct($simpleproduct2);
		$this->assertEquals(2, $product->countSimpleProducts());

	}

	public function test_CanNotAddTwoSkusToConfigurableProduct()
	{
		$this->setExpectedException('Datapump\Exception\ProductSkuAlreadyAdded');

		$product = new Configurable('config-sku', 'color');
		$simpleproduct1 = clone $this->simpleProduct;
		$simpleproduct1->set('color', 'blue');
		$product->addSimpleProduct($simpleproduct1);
		$product->addSimpleProduct($simpleproduct1);
	}

	public function test_GetSimpleproducts()
	{
		$product = new Configurable('config-sku', 'color');

		$simpleproduct1 = clone $this->simpleProduct;
		$simpleproduct1->set('color', 'blue');
		$product->addSimpleProduct($simpleproduct1);

		$simpleproduct2 = clone $this->simpleProduct;
		$simpleproduct2->setSku('sku2');
		$simpleproduct2->set('color', 'green');
		$product->addSimpleProduct($simpleproduct2);

		$this->assertInstanceOf('Datapump\Product\Simple', $product->getSimpleProduct('sku2'));

		$this->assertNull($product->getSimpleProduct('doesnotexists'));

		$this->assertContains('color', $product->getConfigurableAttribute());
	}

	public function test_AddConfigurableProductToHolder()
	{
		$product = new Configurable('config-sku', 'color');
		$product->setVisibility(true, true)
			->setDescription('Foobar')
			->setShortDescription('Foobar')
			->setName('Foobar');

		$simpleproduct1 = clone $this->simpleProduct;
		$simpleproduct1->set('color', 'blue');
		$product->addSimpleProduct($simpleproduct1);

		$simpleproduct2 = clone $this->simpleProduct;
		$simpleproduct2->setSku('sku2');
		$simpleproduct2->set('color', 'green');
		$product->addSimpleProduct($simpleproduct2);

		$this->productHolder->addProduct($product);
	}

}