<?php
/**
 * Created by lsv
 * Date: 9/3/13
 * Time: 4:24 PM
 */

namespace Datapump\Tests;


use Datapump\Product\Data\Category;
use Datapump\Product\Data\RequiredData;
use Datapump\Product\Simple;

class CategoryTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var \Datapump\Product\Data\RequiredData
	 */
	private $simpleRequiredData;

	/**
	 * @var \Datapump\Product\Data\RequiredData
	 */
	private $configRequiredData;

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
	}

	public function test_canAddCategory()
	{
		$product = new Simple(clone $this->simpleRequiredData);

		$category = new Category();
		$category->set('mycategory');

		$product->injectData($category);

		$data = $product->getData();
		$this->assertEquals('mycategory::1::1::1', $data['categories']);

	}

	public function test_canSetNotActive()
	{
		$product = new Simple(clone $this->simpleRequiredData);

		$category = new Category();
		$category->set('mycategory', false, true, true);

		$product->injectData($category);

		$data = $product->getData();
		$this->assertEquals('mycategory::0::1::1', $data['categories']);
	}

	public function test_canSetNotAnchor()
	{
		$product = new Simple(clone $this->simpleRequiredData);

		$category = new Category();
		$category->set('mycategory', true, false, true);

		$product->injectData($category);

		$data = $product->getData();
		$this->assertEquals('mycategory::1::0::1', $data['categories']);
	}

	public function test_canSetNotinMenu()
	{
		$product = new Simple(clone $this->simpleRequiredData);

		$category = new Category();
		$category->set('mycategory', true, true, false);

		$product->injectData($category);

		$data = $product->getData();
		$this->assertEquals('mycategory::1::1::0', $data['categories']);
	}

	public function test_canAddCategoriesLevels()
	{
		$product = new Simple(clone $this->simpleRequiredData);

		$category = new Category();
		$category->set('level1/level2', true, true, true);

		$product->injectData($category);

		$data = $product->getData();
		$this->assertEquals('level1/level2::1::1::1', $data['categories']);
	}

	public function test_canAddCategoriesLevelsWithAnotherDemiliter()
	{
		$product = new Simple(clone $this->simpleRequiredData);

		$category = new Category();
		$category->set('level1*level2', true, true, true, '*');

		$product->injectData($category);

		$data = $product->getData();
		$this->assertEquals('level1/level2::1::1::1', $data['categories']);
	}

	public function test_noCategoryAdded()
	{
		$product = new Simple(clone $this->simpleRequiredData);

		$category = new Category();
		$product->injectData($category);

		$data = $product->getData();
		$this->assertFalse(isset($data['categories']));
	}

}