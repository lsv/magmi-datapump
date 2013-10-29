<?php
/**
 * Created by lsv
 * Date: 8/29/13
 * Time: 1:58 PM
 */

namespace Datapump\Tests;

use Datapump\Product\Data\DataInterface;
use Datapump\Product\Data\RequiredData;
use Datapump\Product\ProductAbstract;
use Datapump\Product\ItemHolder;
use Datapump\Product\Simple;

class ProductTest extends Booter
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
        parent::__construct();

        $this->productholder = new ItemHolder(self::getLogger());

        $requiredData = new RequiredData();
        $requiredData->setSku('sku')
            ->setName('name')
            ->setPrice(100)
            ->setQty(100)
            ->setShortDescription('short description')
            ->setDescription('long description')
            ->setTax(1)
            ->setWeight(100);

        $this->product = new Simple($requiredData);

    }

    public function test_CanCreateSimpleProduct()
    {
        $product = clone $this->product;

        $this->productholder->addProduct($product);

        $this->assertEquals(1, $product->getRequiredData()->getStatus(), 'status error');
        $this->assertEquals(100, $product->getRequiredData()->getPrice(), 'price error');
        $this->assertEquals('sku', $product->getRequiredData()->getSku(), 'sku error');
    }

    public function test_CanTestForMissingFields()
    {
        $this->setExpectedException('Datapump\Exception\MissingProductData');

        $requiredData = new RequiredData();
        $requiredData->setSku('sku');

        $product = new Simple($requiredData);
        $this->productholder->addProduct($product);
    }

    public function test_CanDisableProduct()
    {
        $this->product->getRequiredData()->setDisabled();
        $this->assertEquals(2, $this->product->getRequiredData()->getStatus());
        $this->product->getRequiredData()->setEnabled();
        $this->assertEquals(1, $this->product->getRequiredData()->getStatus());
    }

    public function test_CanSetKey()
    {
        $this->product->set('random', 'foobar');
        $this->assertEquals('foobar', $this->product->get('random'));
    }

    public function test_CanSetOutOfStock()
    {
        $this->product->getRequiredData()->setQty(0);
        $this->assertEquals(0, $this->product->getRequiredData()->getQty());
        $data = $this->product->getRequiredData()->getData();

        $this->assertContains('is_in_stock', $data);
        $this->assertEquals(0, $data['is_in_stock']);
    }

    public function test_canSetManageStock()
    {
        $this->product->getRequiredData()->setQty(null);
        $this->assertEquals(0, $this->product->getRequiredData()->getQty());
        $data = $this->product->getRequiredData()->getData();

        $this->assertContains('manage_stock', $data);
        $this->assertEquals(0, $data['manage_stock'], 'Could not set manage stock');
    }

    public function test_canSetVisibility()
    {
        $this->product->getRequiredData()->setVisibility(true, true);
        $this->assertEquals(
            DataInterface::VISIBILITY_CATALOG_SEARCH,
            $this->product->getRequiredData()->getVisibility(),
            'Visibility both catalog and search'
        );

        $this->product->getRequiredData()->setVisibility(false, true);
        $this->assertEquals(
            DataInterface::VISIBILITY_SEARCH,
            $this->product->getRequiredData()->getVisibility(),
            'Visibility only search'
        );

        $this->product->getRequiredData()->setVisibility(true, false);
        $this->assertEquals(
            DataInterface::VISIBILITY_CATALOG,
            $this->product->getRequiredData()->getVisibility(),
            'Visibility only catalog'
        );

        $this->product->getRequiredData()->setVisibility(false, false);
        $this->assertEquals(
            DataInterface::VISIBILITY_NOTVISIBLE,
            $this->product->getRequiredData()->getVisibility(),
            'No visibility'
        );
    }

    /**
     * @covers Datapump\Product\Data\DataAbstract::__set()
     * @covers Datapump\Product\Data\DataAbstract::__get()
     */
    public function test_canSetDataAsObj()
    {
        $this->product->foobar = 'random';
        $this->assertEquals('random', $this->product->get('foobar'));
        $this->assertEquals('random', $this->product->foobar);
    }

    public function test_canOverwriteSku()
    {
        $this->assertEquals('sku', $this->product->get('sku'));
        $this->product->set('sku', 'sku3');
        $this->assertEquals('sku3', $this->product->get('sku'));
    }

    public function test_canSetAllRequiredData()
    {
        $this->product->getRequiredData()->setStore('mystore');
        $this->assertEquals('mystore', $this->product->getRequiredData()->getStore(), 'store');

        $this->product->getRequiredData()->setAttributeSet('myattrset');
        $this->assertEquals('myattrset', $this->product->getRequiredData()->getAttributeSet(), 'attr set');

        $this->product->getRequiredData()->setDescription('mylongdesc');
        $this->assertEquals('mylongdesc', $this->product->getRequiredData()->getDescription(), 'desc');

        $this->product->getRequiredData()->setShortDescription('myshortdesc');
        $this->assertEquals('myshortdesc', $this->product->getRequiredData()->getShortDescription(), 'short desc');

        $this->product->getRequiredData()->setName('myname');
        $this->assertEquals('myname', $this->product->getRequiredData()->getName(), 'name');

        $this->product->getRequiredData()->setWeight(2.3);
        $this->assertEquals(2.3, $this->product->getRequiredData()->getWeight(), 'weight');

        $this->product->getRequiredData()->setPrice(200.12);
        $this->assertEquals(200.12, $this->product->getRequiredData()->getPrice(), 'price');

        $this->product->getRequiredData()->setTax(5);
        $this->assertEquals(5, $this->product->getRequiredData()->getTax(), 'tax');

        $this->product->getRequiredData()->setQty(25);
        $this->assertEquals(25, $this->product->getRequiredData()->getQty(), 'qty');
    }
}