<?php
/**
 * Created by lsv
 * Date: 8/29/13
 * Time: 5:36 PM
 */

namespace Datapump\Tests;

use Datapump\Product\Configurable;
use Datapump\Product\Data\RequiredData;
use Datapump\Product\Simple;
use Datapump\Product\ItemHolder;

class ProductsHolderTest extends Booter
{

    /**
     * @var RequiredData
     */
    private $requiredData;

    public function __construct()
    {
        parent::__construct();

        $this->requiredData = new RequiredData();
        $this->requiredData->setSku('sku')
            ->setName('name')
            ->setPrice(100)
            ->setQty(100)
            ->setShortDescription('short description')
            ->setDescription('long description')
            ->setTax(1)
            ->setWeight(100);

    }

    public function test_canRemoveProduct()
    {
        $holder = new ItemHolder(self::getLogger());

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
        $holder = new ItemHolder(self::getLogger());

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
        $holder = new ItemHolder(self::getLogger());

        $this->setExpectedException('Datapump\Exception\ProductSkuAlreadyAdded');

        $product1 = new Simple(clone $this->requiredData->setSku('sku-1'));
        $product2 = new Simple(clone $this->requiredData->setSku('sku-1'));

        $holder->addProduct($product1)
            ->addProduct($product2);
    }

    public function test_canNotAddMoreWithSameSkuFromArray()
    {
        $holder = new ItemHolder(self::getLogger());

        $this->setExpectedException('Datapump\Exception\ProductSkuAlreadyAdded');

        $product1 = new Simple(clone $this->requiredData->setSku('sku-1'));
        $product2 = new Simple(clone $this->requiredData->setSku('sku-1'));

        $holder->addProduct(array($product1, $product2));
    }

    public function test_canNotAddAObjectWhichIsNotAProductAbstract()
    {
        $this->setExpectedException('Datapump\Exception\ProductNotAnArrayOrProductAbstract');

        $holder = new ItemHolder(self::getLogger());
        $product1 = new \stdClass();
        $product2 = new \stdClass();
        $holder->addProduct($product1);

        $holder->addProduct(array($product1, $product2));
    }

    public function test_canCountProducts()
    {
        $holder = new ItemHolder(self::getLogger());
        $product1 = new Simple(clone $this->requiredData->setSku('sku-1')->set('color', 'blue'));
        $product2 = new Simple(clone $this->requiredData->setSku('sku-2')->set('color', 'red'));
        $product3 = new Simple(clone $this->requiredData->setSku('sku-3'));

        $req = new RequiredData();
        $req->setSku('sku')
            ->setName('name')
            ->setShortDescription('short description')
            ->setDescription('long description');
        $product4 = new Configurable($req, 'color');
        $product4->addSimpleProduct($product1)
            ->addSimpleProduct($product2);

        $holder->addProduct(array($product3, $product4));

        $this->assertEquals(4, $holder->countProducts());
    }

    public function test_canDebug()
    {
        $holder = new ItemHolder(self::getLogger());

        $product1 = new Simple(clone $this->requiredData->setSku('sku-1'));
        $product2 = new Simple(clone $this->requiredData->setSku('sku-2'));
        $product3 = new Simple(clone $this->requiredData->setSku('sku-3'));

        $holder->addProduct(array($product1, $product2, $product3));
        $debug = $holder->import(true);
        $this->assertTrue(is_array($debug));

    }
}