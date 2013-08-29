<?php
/**
 * Created by lsv
 * Date: 8/29/13
 * Time: 5:36 PM
 */

namespace Datapump\Tests;

use Datapump\Product\Simple;
use Datapump\Product\ItemHolder;

class ProductsHolderTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var Simple
	 */
	private $product;

	/**
	 * @var ItemHolder
	 */
	private $holder;

	public function __construct()
	{
		$this->product = new Simple('sku');
		$this->product->setDescription('Description')
			->setName('name')
			->setPrice(100)
			->setQty(100)
			->setShortDescription('short description')
			->setTax(1)
			->setWeight(100);

		$this->holder = new ItemHolder;

		parent::__construct();
	}

	public function test_canRemoveProduct()
	{
		$product1 = clone $this->product;
		$product1->setSku('sku-1');
		$product2 = clone $this->product;
		$product2->setSku('sku-2');
		$product3 = clone $this->product;
		$product3->setSku('sku-3');

		$this->holder->addProduct($product1)
			->addProduct($product2)
			->addProduct($product3);

		$this->assertTrue($this->holder->removeProduct('sku-2'));

		$this->assertInstanceOf('Datapump\Product\ProductAbstract', $this->holder->findProduct('sku-1'));
		$this->assertFalse($this->holder->removeProduct('foobar'));

		$this->assertInstanceOf('Datapump\Product\ItemHolder', $this->holder->addProduct($product2));

	}

	public function test_canFindProduct()
	{
		$product1 = clone $this->product;
		$product1->setSku('sku-1');
		$product2 = clone $this->product;
		$product2->setSku('sku-2');
		$product3 = clone $this->product;
		$product3->setSku('sku-3');

		$this->holder->addProduct($product1)
			->addProduct($product2)
			->addProduct($product3);

		$this->assertInstanceOf('Datapump\Product\ProductAbstract', $this->holder->findProduct('sku-1'));
		$this->assertTrue($this->holder->removeProduct('sku-2'));
		$this->assertFalse($this->holder->findProduct('sku-2'));

	}

	public function test_canNotAddMoreWithSameSku()
	{
		$this->setExpectedException('Datapump\Exception\ProductSkuAlreadyAdded');

		$product1 = clone $this->product;
		$product1->setSku('sku-1');
		$product2 = clone $this->product;
		$product2->setSku('sku-1');

		$this->holder->addProduct($product1);
		$this->holder->addProduct($product2);
	}

}