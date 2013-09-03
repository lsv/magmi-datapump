<?php
/**
 * Created by lsv
 * Date: 8/29/13
 * Time: 4:24 PM
 */

namespace Datapump\Tests;

use Datapump\Product\Data\RequiredData;
use Datapump\Product\ItemHolder;
use Datapump\Product\Simple;
use Datapump\Product\Configurable;

class ConfigurableTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var RequiredData
	 */
	protected $configRequiredData;

	/**
	 * @var RequiredData
	 */
	protected $simpleRequiredData;

	public function __construct()
	{
		$this->simpleRequiredData = new RequiredData();
		$this->simpleRequiredData->setSku('sku')
			->setName('name')
			->setPrice(100)
			->setQty(100)
			->setShortDescription('short description')
			->setDescription('long description')
			->setTax(1)
			->setWeight(100);

		$this->configRequiredData = new RequiredData();
		$this->configRequiredData->setSku('sku')
			->setName('name')
			->setShortDescription('short description')
			->setDescription('long description');

		parent::__construct();
	}

	public function test_SimpleProductMissingKey()
	{
		$this->setExpectedException('Datapump\Exception\SimpleProductMissingConfigurableAttribute');
		$product = new Configurable($this->configRequiredData, 'color');
		$product->addSimpleProduct(new Simple($this->simpleRequiredData));
	}

	public function test_SimpleProductHasKey()
	{
		$product = new Configurable($this->configRequiredData, 'color');

		$simpleproduct1 = new Simple(clone $this->simpleRequiredData->setSku('sku'));
		$simpleproduct1->set('color', 'blue');
		$product->addSimpleProduct($simpleproduct1);
		$this->assertEquals(1, $product->countSimpleProducts());
		$this->assertEquals('sku', $simpleproduct1->get('sku'));

		$simpleproduct2 = new Simple(clone $this->simpleRequiredData->setSku('sku2'));
		$simpleproduct2->set('color', 'green');
		$product->addSimpleProduct($simpleproduct2);
		$this->assertEquals(2, $product->countSimpleProducts());
		$this->assertEquals('sku', $simpleproduct1->get('sku'));

	}

	public function test_CanNotAddTwoSkusToConfigurableProduct()
	{
		$this->setExpectedException('Datapump\Exception\ProductSkuAlreadyAdded');

		$product = new Configurable($this->configRequiredData, 'color');
		$simpleproduct1 = new Simple(clone $this->simpleRequiredData);
		$simpleproduct1->set('color', 'blue');
		$product->addSimpleProduct($simpleproduct1);
		$product->addSimpleProduct($simpleproduct1);
	}

	public function test_GetSimpleproducts()
	{
		$product = new Configurable($this->configRequiredData, 'color');

		$simpleproduct1 = new Simple(clone $this->simpleRequiredData);
		$simpleproduct1->set('color', 'blue');
		$product->addSimpleProduct($simpleproduct1);

		$simpleproduct2 = new Simple(clone $this->simpleRequiredData);
		$simpleproduct2->set('sku','sku2');
		$simpleproduct2->set('color', 'green');
		$product->addSimpleProduct($simpleproduct2);

		$this->assertInstanceOf('Datapump\Product\Simple', $product->getSimpleProduct('sku2'));

		$this->assertNull($product->getSimpleProduct('doesnotexists'));

		$this->assertContains('color', $product->getConfigurableAttribute());
	}

	public function test_AddConfigurableProductToHolder()
	{
		$product = new Configurable($this->configRequiredData, 'color');

		$simpleproduct1 = new Simple(clone $this->simpleRequiredData);
		$simpleproduct1->set('color', 'blue');
		$product->addSimpleProduct($simpleproduct1);

		$simpleproduct2 = new Simple(clone $this->simpleRequiredData);
		$simpleproduct2->set('sku','sku2');
		$simpleproduct2->set('color', 'green');
		$product->addSimpleProduct($simpleproduct2);

		$productholder = new ItemHolder;
		$productholder->addProduct($simpleproduct1)
			->addProduct($simpleproduct2);
	}

}