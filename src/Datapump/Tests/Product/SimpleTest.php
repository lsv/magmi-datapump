<?php
/**
 * Created by lsv
 * Date: 8/29/13
 * Time: 1:58 PM
 */

namespace Datapump\Tests;

use Datapump\Product\ProductAbstract;
use Datapump\Product\ItemHolder;
use Datapump\Product\Simple;

class ProductTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var ItemHolder
	 */
	private $productholder;

	/**
	 * @var Simple
	 */
	private $product;

	public function __construct()
	{
		$this->productholder = new ItemHolder();

		$this->product = new Simple('sku');
		$this->product->setDescription('Description')
			->setName('name')
			->setPrice(100)
			->setQty(100)
			->setShortDescription('short description')
			->setTax(1)
			->setWeight(100);

		parent::__construct();
	}

	public function test_CanCreateSimpleProduct()
	{

		$product = new Simple('sku');
		$product->setDescription('Description')
			->setName('name')
			->setPrice(100)
			->setQty(100)
			->setShortDescription('short description')
			->setTax(1)
			->setWeight(100);

		$this->productholder->addProduct($product);

		$this->assertEquals(1, $product->getStatus());
		$this->assertEquals(100, $product->getPrice());
		$this->assertEquals('sku', $product->getSku());

	}

	public function test_CanTestForMissingFields()
	{
		$this->setExpectedException('Datapump\Exception\MissingProductData');

		$product = new Simple('sku');
		$this->productholder->addProduct($product);
	}

	public function test_CanDisableProduct()
	{
		$this->product->setDisabled();
		$this->assertEquals(2, $this->product->getStatus());
		$this->product->setEnabled();
		$this->assertEquals(1, $this->product->getStatus());
	}

	public function test_CanSetKey()
	{
		$this->product->set('random', 'foobar');
		$this->assertEquals('foobar', $this->product->get('random'));
	}

	public function test_CanSetOutOfStock()
	{
		$this->product->setQty(0);
		$this->assertEquals(0, $this->product->getQty());
		$data = $this->product->getData();

		$this->assertContains('is_in_stock', $data);
		$this->assertEquals(0, $data['is_in_stock']);

	}

	public function test_canSetManageStock()
	{
		$this->product->setQty(null);
		$this->assertEquals(0, $this->product->getQty());
		$data = $this->product->getData();

		$this->assertContains('manage_stock', $data);
		$this->assertEquals(0, $data['manage_stock'], 'Could not set manage stock');
	}

	public function test_canSetVisibility()
	{
		$this->product->setVisibility(true, true);
		$this->assertEquals(ProductAbstract::VISIBILITY_CATALOG_SEARCH, $this->product->getVisibility(), 'Visibility both catalog and search');

		$this->product->setVisibility(false, true);
		$this->assertEquals(ProductAbstract::VISIBILITY_SEARCH, $this->product->getVisibility(), 'Visibility only search');

		$this->product->setVisibility(true, false);
		$this->assertEquals(ProductAbstract::VISIBILITY_CATALOG, $this->product->getVisibility(), 'Visibility only catalog');

		$this->product->setVisibility(false, false);
		$this->assertEquals(ProductAbstract::VISIBILITY_NOTVISIBLE, $this->product->getVisibility(), 'No visibility');

	}

	public function test_canSetDataAsObj()
	{
		$this->product->foobar = 'random';
		$this->assertEquals('random', $this->product->get('foobar'));
		$this->assertEquals('random', $this->product->foobar);
	}

	public function test_canDebug()
	{
		$data = $this->product->debug();
		$this->assertNotEmpty($data);
		$this->assertStringStartsWith('array', $data);
	}

}