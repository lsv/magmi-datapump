<?php
/**
 * Created by lsv
 * Date: 8/29/13
 * Time: 5:36 PM
 */

namespace Datapump\Tests;

use Datapump\Product\Data\RequiredData;
use Datapump\Product\Simple;
use Datapump\Product\ItemHolder;

class ProductsHolderTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var RequiredData
	 */
	private $requiredData;

	public function __construct()
	{

		$this->requiredData = new RequiredData();
		$this->requiredData->setSku('sku')
			->setName('name')
			->setPrice(100)
			->setQty(100)
			->setShortDescription('short description')
			->setDescription('long description')
			->setTax(1)
			->setWeight(100);

		parent::__construct();
	}

	public function test_canRemoveProduct()
	{
		$holder = new ItemHolder;

		$product1 = new Simple(clone $this->requiredData->setSku('sku-1'));
		$product2 = new Simple(clone $this->requiredData->setSku('sku-2'));
		$product3 = new Simple(clone $this->requiredData->setSku('sku-3'));

		$holder->addProduct($product1)
			->addProduct($product2)
			->addProduct($product3);

		$this->assertTrue($holder->removeProduct('sku-2'), 'remove product');

		$this->assertInstanceOf('Datapump\Product\ProductAbstract', $holder->findProduct('sku-1'), 'find product');
		$this->assertFalse($holder->removeProduct('foobar'), 'remove product foobar');

		$this->assertInstanceOf('Datapump\Product\ItemHolder', $holder->addProduct($product2));

	}

	public function test_canFindProduct()
	{
		$holder = new ItemHolder;

		$product1 = new Simple(clone $this->requiredData->setSku('sku-1'));
		$product2 = new Simple(clone $this->requiredData->setSku('sku-2'));
		$product3 = new Simple(clone $this->requiredData->setSku('sku-3'));

		$holder->addProduct(array($product1, $product2, $product3));

		$this->assertInstanceOf('Datapump\Product\ProductAbstract', $holder->findProduct('sku-1'));
		$this->assertTrue($holder->removeProduct('sku-2'));
		$this->assertFalse($holder->findProduct('sku-2'));

	}

	public function test_canNotAddMoreWithSameSku()
	{
		$holder = new ItemHolder;

		$this->setExpectedException('Datapump\Exception\ProductSkuAlreadyAdded');

		$product1 = new Simple(clone $this->requiredData->setSku('sku-1'));
		$product2 = new Simple(clone $this->requiredData->setSku('sku-1'));

		$holder->addProduct($product1)
			->addProduct($product2);
	}

	public function test_canNotAddMoreWithSameSkuFromArray()
	{
		$holder = new ItemHolder;

		$this->setExpectedException('Datapump\Exception\ProductSkuAlreadyAdded');

		$product1 = new Simple(clone $this->requiredData->setSku('sku-1'));
		$product2 = new Simple(clone $this->requiredData->setSku('sku-1'));

		$holder->addProduct(array($product1, $product2));
	}

	public function test_canNotAddAObjectWhichIsNotAProductAbstract()
	{
		$this->setExpectedException('Datapump\Exception\ProductNotAnArrayOrProductAbstract');

		$holder = new ItemHolder;
		$product1 = new \stdClass();
		$product2 = new \stdClass();
		$holder->addProduct($product1);

		$holder->addProduct(array($product1, $product2));
	}
}