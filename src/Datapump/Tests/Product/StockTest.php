<?php
/**
 * Created by PhpStorm.
 * User: lsv
 * Date: 9/11/13
 * Time: 1:36 PM
 */

namespace Datapump\Tests;

use Datapump\Product\Data\RequiredData;
use Datapump\Product\ItemHolder;
use Datapump\Product\Stock;

class StockTest extends Booter
{

    private $productholder;

    public function __construct()
    {
        $this->productholder = new ItemHolder(self::getLogger());
    }

    public function test_canAddStockChangeProduct()
    {
        $product1 = new Stock(new RequiredData());
        $product1->set('sku', 'stock-sku1')->set('qty', 10);

        $product2 = new Stock(new RequiredData());
        $product2->set('sku', 'stock-sku2')->set('qty', 30);

        $this->productholder->addProduct($product1)->addProduct($product2);
        /** @var Stock $product */
        $product = $this->productholder->findProduct('stock-sku1');
        $this->assertEquals(10, $product->get('qty'));

    }

} 