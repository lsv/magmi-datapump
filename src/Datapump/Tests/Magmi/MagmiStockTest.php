<?php
/**
 * Created by lsv
 * Date: 9/4/13
 * Time: 6:29 AM
 */

namespace Datapump\Tests\Magmi;

use Datapump\Product\Data\RequiredData;
use Datapump\Product\ItemHolder;
use Datapump\Product\Stock;
use Datapump\Tests\Booter;

/**
 * @requires extension mysqli
 */
class MagmiStockTest extends Booter
{

    public function __construct()
    {
        parent::__construct();
        parent::initializeDatabase();

        $this->itemholder = new ItemHolder(self::getLogger());
        $this->itemholder->setMagmi(
            \Magmi_DataPumpFactory::getDataPumpInstance("productimport"),
            'travis',
            ItemHolder::MAGMI_CREATE_UPDATE
        );

    }

    public function test_canInjectStockData()
    {
        $product1 = new Stock(new RequiredData());
        $product1->set('sku', 'stock-sku1')->set('qty', 10);

        $product2 = new Stock(new RequiredData());
        $product2->set('sku', 'stock-sku2')->set('qty', 30);

        $this->itemholder->addProduct($product1)->addProduct($product2);
        $this->itemholder->import();

    }

}